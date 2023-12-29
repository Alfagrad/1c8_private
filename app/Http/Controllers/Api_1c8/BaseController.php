<?php

namespace App\Http\Controllers\Api_1c8;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;
use Intervention\Image\Facades\Image;
use App\Helpers\XMLHelper;

use App\Models\Discount;
use App\Models\DiscountValue;
use App\Models\DiscountAgreement;
use App\Models\DiscountProduct;
use App\Models\Item;

class BaseController extends Controller
{
    public $xml;
    public $xml_data;
    public $has_error = false;
    public $type_action = '';

    // public $login = '1csite';
    // public $password = '1csite';


    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><data></data>');
        $this->xml->addAttribute('version', '1.0');

    }

    public function getOrDefault(string $key, $default)
    {
        $val = trim($this->xml_data->$key);
        return (func_num_args() == 2 && empty($val)) ? $default : $val;
    }

    protected function prepareData(object $data): array
    {
        return array_map('trim', array_filter((array)$data));
    }

    protected function logStart(string $method, string $log = ''): string
    {
        $message = $log . ' ' . $method . '. Xml received at '.nowTime();
        Log::info($message);
        return $message;
    }

    protected function logLoopStarts(string $log = ''): string
    {
        $message = $log.'. Loop started at '.nowTime();
        Log::info($message);
        return $message;
    }

    protected function logLoopEnds(string $log = ''): string
    {
        $message = $log.'. Loop ended at '.nowTime();
        Log::info($message);
        return $message;
    }

    protected function logCount(string $log, array $items): string
    {
        $message = $log. ' with count(' . count($items) . ')';
        Log::info($message);
        return $message;
    }

    public function xml_auth($request)
    {
        if ($request->header('login') !== '1csite' || $request->header('password') !== '1csite') {

            $response = new Response('Неверный логин или пароль!!!', 200);
            return $response;
        }
    }

    function valid_empty($req_fields)
    {
        $err_str = "";
        $err = 0;

        foreach ($req_fields as $f) {
            if (trim($this->xml_data->{$f}) == '') {
                $err = 1;
                $err_str .= "'{$f}' ";
            }
        }

        if ($err) {
            $err_str = "Поле {$err_str}не может быть пустым!";
            $response = new Response($err_str, 200);
            return $response;
        }
    }

    // обработка изображений ***************************************************
    public function imageHandler($img_input_name, $img_output_name, $input_path, $output_path, $width, $height)
    {
        // $img_input_name - имя исходного файла
        // $img_output_name - имя полученного файла
        // $input_path - путь где брать файл
        // $output_path - путь куда положить
        // $width - ширина для исходящего изобр, или пусто
        // $height - высота для исходящего изобр, или пусто

        // если параметр ширины не пустой
        if ($width) {
            // смотрим ширину входящего файла
            $input_width = Image::make($input_path . $img_input_name)->width();

            // если меньше, оставляем меньшую
            if ($input_width <= $width) {
                $width = $input_width;
            }
        } else {
            $width = null;
        }

        if (!$height)
            $height = null;

        $img_result = Image::make($input_path . $img_input_name)
            ->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($output_path . $img_output_name);
    }

    // обработка всех дисконтных товаров (формирование строк дисконта)
    // получение дисконтов для товаров ******************************************
    static public function allCommonDiscountStrings()
    {

        // очищаем строки дисконтов в БД
        Item::where('discount_str', '!=', 'null')->update([
            'discount_str' => null,
            'discount_date' => '',
        ]);

        // берем uuid общих дисконтов
        $common_discount_uuids = DiscountAgreement::where('agreement_uuid', '')->pluck('discount_uuid')->toArray();

        // если есть
        if (count($common_discount_uuids)) {

            // берем uuid товаров дисконта
            $product_uuids = DiscountProduct::whereIn('discount_uuid', $common_discount_uuids)->pluck('item_uuid')->toArray();

            // если не пусто
            if (count($product_uuids)) {

                foreach ($product_uuids as $item_uuid) {

                    // обновляем информацию о дисконте у товаров
                    self::getCommonDiscounts($item_uuid);

                }
            }
        }
    }

    // получение дисконтов для товаров ******************************************
    static public function getCommonDiscounts($item_uuid)
    {

        // берем курс usd
        $usd_opt = setting('header_usd');

        // берем uuid дисконтов товара
        $item_discount_uuids = DiscountProduct::where('item_uuid', $item_uuid)->pluck('discount_uuid')->toArray();

        // если есть
        if (count($item_discount_uuids)) {

            // проверяем, является ли дисконт общим / не общим
            foreach ($item_discount_uuids as $key => $uuid) {

                // берем соглашение
                $agreement = DiscountAgreement::where([['discount_uuid', $uuid], ['agreement_uuid', '']])->first(['id']);

                // если нет
                if (!$agreement) {

                    // удаляем uuid дисконта из массива
                    unset($item_discount_uuids[$key]);

                    // очищаем поля с данными о дисконте
                    Item::where('uuid', $item_uuid)->update([
                        'discount_str' => null,
                        'discount_date' => null,
                    ]);

                }
            }
        }

        // если остались дисконты
        if (count($item_discount_uuids)) {

            // берем товар
            $item = Item::where('uuid', $item_uuid)->first();

            // если есть
            if ($item) {

                // собираем значения дисконтов
                $item_discounts = DiscountValue::whereIn('discount_uuid', $item_discount_uuids)
                    ->orderBy('condition')
                    ->orderBy('value', 'desc')
                    ->get();

                // формируем строку дисконта
                $discount_str = '';
                $i = 1;
                $discount_count = $item_discounts->count();
                $discount_date = '0000-00-00'; // дата акции

                // для исключения повторов condition
                $con = '';

                foreach ($item_discounts as $value) {

                    // если предыдущий condition == текущему, пропускаем
                    if ($con == $value->condition) {
                        $discount_count--;
                        continue;
                    }

                    // считаем цену
                    if ($item->adjustable == 1) { // если регулируемая цена
                        $price = number_format($item->price_rub * (100 - $value->value) / 100, 2, '.', '');
                    } else {
                        $price = number_format($item->price_usd * $usd_opt * (100 - $value->value) / 100, 2, '.', '');
                    }

                    // берем даты действия дисконта для товара
                    $item_dates = DiscountProduct::where([['discount_uuid', $value->discount_uuid], ['item_uuid', $item_uuid]])->first(['date_start', 'date_end']);

                    // берем даты действия дисконта для соглашения
                    $agreement_dates = DiscountAgreement::where('discount_uuid', $value->discount_uuid)->first(['date_start', 'date_end']);

                    // опредеряем дату для старта
                    if ($item_dates->date_start > $agreement_dates->date_start) {
                        $date_start = $item_dates->date_start;
                    } else {
                        $date_start = $agreement_dates->date_start;
                    }

                    // опредеряем дату для окончания
                    if ($agreement_dates->date_end == '0000-00-00' || $item_dates->date_end < $agreement_dates->date_end) {

                        $date_end = $item_dates->date_end;

                    } else {

                        $date_end = $agreement_dates->date_end;

                    }

                    $discount_str .= "{$value->condition}|{$price}|{$date_start}|{$date_end}|{$value->value}";

                    if ($i < $discount_count) {
                        $discount_str .= ';';
                    }

                    if ($date_start > $discount_date) {
                        $discount_date = $date_start;
                    }

                    $con = $value->condition;

                    $i++;
                }

                // обновляем товар
                $item->update([
                    'discount_str' => $discount_str,
                    'discount_date' => $discount_date,
                ]);

            }
        }
    }

}
