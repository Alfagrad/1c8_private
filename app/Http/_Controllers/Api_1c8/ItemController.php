<?php

namespace App\Http\Controllers\Api_1c8;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\XMLHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;

use App\Models\Item;
use App\Models\CharacteristicItem;
use App\Models\ItemImage;
use App\Models\ItemGuide;
use TCG\Voyager\Models\Setting;


class ItemController extends BaseController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function postCreateOrUpdate(XMLHelper $XMLHelper, Request $request)
    {

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // поля в XML, которые должны быть заполнены
        $requared_fields = [
            'id_1c',
            'category_id_1c',
            'name',
            'is_component',
        ];

        // проверка на пустоту
        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        $uuid = trim($this->xml_data->uuid);

        $type = trim($this->xml_data->type);
        $edition = intval(trim($this->xml_data->edition));

        // формируем id_1с товара
//        $id_1c = intval(explode('-', trim($this->xml_data->id_1c))[1]);
        $id_1c = trim($this->xml_data->id_1c);
        if ($type == 'edition') {
            $id_1c = $id_1c . "-" . $edition;
        }

        $category_id = intval(trim($this->xml_data->category_id_1c));
        // $category_id = intval(explode('-', trim($this->xml_data->category_id_1c))[1]);

        // собираем данные
        $data = array(
            'is_component' => intval($this->xml_data->is_component),
            'in_archive' => intval(trim($this->xml_data->in_archive)),
            'in_price' => intval(trim($this->xml_data->in_priсe)),
            // влияет только на архивные
            'id_1c' => $id_1c,
            'uuid' => $uuid,
            'category_id_1c' => $category_id,
            'type' => $type,
            'edition' => $edition,
            'name' => trim($this->xml_data->name),
            'content' => trim($this->xml_data->content),
            'more_about' => trim($this->xml_data->more_about),
            'vendor_code' => trim($this->xml_data->vendor_code),
            'barcode' => trim($this->xml_data->barcode),
            'codeTNVD' => trim($this->xml_data->codeTNVD),
            'country' => trim($this->xml_data->country),
            'apply' => trim($this->xml_data->apply),
            'shelf_life' => trim($this->xml_data->life_time),
            'guarantee_period' => intval($this->xml_data->guarantee_period),
            'equipment' => trim($this->xml_data->equipment),
            'weight' => trim($this->xml_data->weight),
            'netto' => trim($this->xml_data->netto),
            'width' => intval(trim($this->xml_data->width)),
            'depth' => intval(trim($this->xml_data->depth)),
            'height' => intval(trim($this->xml_data->height)),
            'packaging' => intval(trim($this->xml_data->packaging)),
            'youtube' => trim($this->xml_data->video),
            'brand' => trim($this->xml_data->brand),
            'factory' => trim($this->xml_data->factory),
            'is_new_item' => intval(trim($this->xml_data->is_new_product)),
            'date_new_item' => trim($this->xml_data->date_new_product),
            'buy_with' => trim($this->xml_data->buy_with),
            'forget_buy' => trim($this->xml_data->forget_buy),
            'cheap_goods' => trim($this->xml_data->cheap_goods),
            'certificate' => trim($this->xml_data->cert_name),
            'certificate_exp' => trim($this->xml_data->cert_end_date),
            'synonyms' => trim($this->xml_data->synonyms),
            'schemes' => trim($this->xml_data->schemes),
            'importer' => intval(trim($this->xml_data->importer)),
            'adjustable' => intval(trim($this->xml_data->adjustable)),
            'spec_price' => intval(trim($this->xml_data->spec_price)),
            'analog_conanalogtainer_uuid' => trim($this->xml_data->analogs),
            // 'is_action' => intval(trim($this->xml_data->is_action)),
            // 'date_open_action' => $date_open_action,
            // 'mini_text' => trim($this->xml_data->mini_text),
            // 'discounted' => $discounted,
            // 'count' => intval(trim($this->xml_data->count)),
            // 'count_type' => intval(trim($this->xml_data->count_type)),
            // 'count_text' => trim($this->xml_data->count_text),
        );

        // путь где лежит главное изображение
//        $input_puth = '/home/alfastok/public_html/storage/ut_1c8/common/';
        $input_puth = public_path().'/storage/ut_1c8/common/';
        // путь куда положим полученный файл
//        $output_puth = '/home/alfastok/public_html/storage/ut_1c8/item-images/';
        $output_puth = public_path().'/storage/ut_1c8/item-images/';
        // имя исходного файла
        $img_input_name = trim($this->xml_data->image);

        // если есть изображение, обрабатываем
        if (!empty($img_input_name)) {

            // префикс, если есть edition
            if ($edition) {
                $edition_prefix = '-' . $edition;
            } else {
                $edition_prefix = '';
            }

            // имя полученного большого файла
            $img_output_name = explode('.', $img_input_name)[0] . $edition_prefix  . '.jpg';
//            $img_output_name = explode('.', $img_input_name)[0] . $edition_prefix . '_' . time() . '.jpg';
            // имя полученного среднего файла
            $img_output_name_mid = explode('.', $img_input_name)[0] . $edition_prefix . '_mid_' . time() . '.jpg';
            // имя полученного малого файла
            $img_output_name_sm = explode('.', $img_input_name)[0] . $edition_prefix . '_sm_' . time() . '.jpg';

            // проверяем, есть ли главное изображение в хранилище
            // если нет
            if (!file_exists($input_puth . $img_input_name)) {
                // вернем ошибку
                $response = new Response("Ошибка! Для товара {$uuid} главное изображение в хранилище отсутствует! Повторите отправку! ".$input_puth . $img_input_name, 400);
                return $response;
            } else {
                // ресайзим и переносим большое изображение
                $this->imageHandler($img_input_name, $img_output_name, $input_puth, $output_puth, '1600', '');
                // ресайзим и переносим малое изображение
                $this->imageHandler($img_input_name, $img_output_name_mid, $input_puth, $output_puth, '', '300');
                // ресайзим и переносим малое изображение
                $this->imageHandler($img_input_name, $img_output_name_sm, $input_puth, $output_puth, '', '70');

                // берем имя старых изображений
                $old_images = Item::where('uuid', $uuid)->first(['image', 'image_mid', 'image_sm']);
                // если не пусто
                if ($old_images) {
                    // удаляем старые изображения
                    @unlink($output_puth . $old_images->image);
                    @unlink($output_puth . $old_images->image_mid);
                    @unlink($output_puth . $old_images->image_sm);
                }

                // удаляем входящее изображение из хранилища
                @unlink($input_puth . $img_input_name);

                // добавляем в дату имена файлов
                $data['image'] = $img_output_name;
                $data['image_mid'] = $img_output_name_mid;
                $data['image_sm'] = $img_output_name_sm;
            }
        } else { // если в xml нет имени картинки

            // берем имя старых изображений
            $old_images = Item::where('uuid', $uuid)->first(['image', 'image_mid', 'image_sm']);
            // если не пусто
            if ($old_images) {
                // удаляем старые изображения
                @unlink($output_puth . $old_images->image);
                @unlink($output_puth . $old_images->image_mid);
                @unlink($output_puth . $old_images->image_sm);
            }

            // добавляем в дату имена файлов
            $data['image'] = "";
            $data['image_mid'] = "";
            $data['image_sm'] = "";
        }

        if ($this->xml_data->characteristics->characteristic->count()) {

            // удаляем имеющиеся характеристики
            CharacteristicItem::where('item_1c_id', $id_1c)->delete();

            // переписываем заново
            foreach ($this->xml_data->characteristics->characteristic as $characteristic) {

                $data_ch = array(
                    'item_1c_id' => $id_1c,
                    'characteristic_uuid' => trim($characteristic->uuid),
                    'value' => trim($characteristic->value),
                );

                CharacteristicItem::insert($data_ch);
            }
        }

        // обновляем товар
        try {
            $result = Item::updateOrCreate(['uuid' => $uuid], $data);
        } catch (\Exception $e) {
            $response = new Response($e->getMessage(), 400);
        }

        if ($result) {
            $response = new Response("Товар {$uuid} добавлен(обновлен) успешно!", 200);
        } else {
            $response = new Response("Ошибка! Товар {$uuid} НЕ добавлен! Повторите отправку!", 400);
        }

        return $response;

    }

    public function postDelete(XMLHelper $XMLHelper, Request $request)
    {
        return new Response("Обработчик еще не создан!", 200);
    }

    public function itemFiles(XMLHelper $XMLHelper, Request $request)
    {
        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // поля в XML, которые должны быть заполнены
        $requared_fields = [
            'uuid',
        ];

        // проверка на пустоту
        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        $item_uuid = trim($this->xml_data->uuid);

        // путь где лежат файлы
        $input_puth = '/home/alfastok/public_html/storage/ut_1c8/common/';
        // путь куда положим полученный файл
        $output_puth = '/home/alfastok/public_html/storage/ut_1c8/item-images/';

        if (isset($this->xml_data->images->image) && $this->xml_data->images->image->count()) {

            // берем старые изображения
            $old_images = ItemImage::where('item_uuid', $item_uuid)->get();
            // если не пусто
            if ($old_images->count()) {
                foreach ($old_images as $value) {
                    // удаляем старые изображения
                    @unlink($output_puth . $value->image);
                    @unlink($output_puth . $value->image_mid);
                    @unlink($output_puth . $value->image_sm);
                }
            }

            // удаляем имена изображений из БД
            ItemImage::where('item_uuid', $item_uuid)->delete();

            // формируем ответ
            $answer = "";

            // переписываем заново
            foreach ($this->xml_data->images->image as $image) {

                // имя исходного файла
                $img_input_name = trim($image);
                // имя полученного большого файла
                $img_output_name = explode('.', $img_input_name)[0] . '_' . time() . '.jpg';
                // имя полученного среднего файла
                $img_output_name_mid = explode('.', $img_input_name)[0] . '_mid_' . time() . '.jpg';
                // имя полученного малого файла
                $img_output_name_sm = explode('.', $img_input_name)[0] . '_sm_' . time() . '.jpg';

                // проверяем, есть ли изображение в хранилище
                // если нет
                if (!file_exists($input_puth . $img_input_name)) {

                    // формируем сообщение, добавляем к ответу
                    $answer .= "Изображение {$img_input_name} для товара {$item_uuid} в хранилище отсутствует!\n";

                } else {
                    // ресайзим и переносим изображение
                    $this->imageHandler($img_input_name, $img_output_name, $input_puth, $output_puth, '1600', '');
                    // ресайзим и переносим малое изображение
                    $this->imageHandler($img_input_name, $img_output_name_mid, $input_puth, $output_puth, '', '170');
                    // ресайзим и переносим малое изображение
                    $this->imageHandler($img_input_name, $img_output_name_sm, $input_puth, $output_puth, '', '70');

                    // собираем данные
                    $data_img = array(
                        'item_uuid' => $item_uuid,
                        'image' => $img_output_name,
                        'image_mid' => $img_output_name_mid,
                        'image_sm' => $img_output_name_sm,
                    );

                    // добавляем в БД
                    ItemImage::insert($data_img);

                    // удаляем входящее изображение из хранилища
                    @unlink($input_puth . $img_input_name);

                    // формируем ответ
                    $answer .= "Изображение {$img_input_name} к товару {$item_uuid} добавлено успешно!\n";

                }
            }
        }

        // обрабатываем инструкции, если имеются
        if (isset($this->xml_data->guides->guide) && $this->xml_data->guides->guide->count()) {

            // путь куда положим полученный файл
            $output_puth = '/home/alfastok/public_html/storage/ut_1c8/item-guides/';

            // удаляем имена инструкций из БД
            ItemGuide::where('item_uuid', $item_uuid)->delete();

            // переписываем заново
            foreach ($this->xml_data->guides->guide as $guide) {

                // проверяем, есть ли файл в хранилище
                // если нет
                if (!file_exists($input_puth . $guide)) {

                    // формируем сообщение, добавляем к ответу
                    $answer .= "Инструкция {$guide} для товара {$item_uuid} в хранилище отсутствует!\n";

                } else {

                    // собираем данные
                    $data_gu = array(
                        'item_uuid' => $item_uuid,
                        'file' => $guide,
                    );

                    // добавляем в БД
                    ItemGuide::insert($data_gu);

                    // перносим файл
                    rename($input_puth . $guide, $output_puth . $guide);

                    // формируем ответ
                    $answer .= "Инструкция {$guide} к товару {$item_uuid} добавлена успешно!\n";
                }
            }
        }

        $response = new Response($answer, 200);

        return $response;
    }

    public function syncItems(XMLHelper $XMLHelper, Request $request)
    {

        // устанавливаем таймаут на бесконечность
        ini_set('max_execution_time', '0');

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если в запросе есть товары
        if ($this->xml_data->product->count()) {

            // путь где лежит главное изображение
//            $input_puth = '/home/alfastok/public_html/storage/ut_1c8/common/';
            $input_puth = public_path().'/storage/ut_1c8/common/';
            // путь куда положим полученный файл
//            $output_puth = '/home/alfastok/public_html/storage/ut_1c8/item-images/';
            $output_puth = public_path().'/storage/ut_1c8/item-images/';

            // обрабатываем
            foreach ($this->xml_data->product as $product) {
                // формируем id_1с товара
//                $id_1c = intval(explode('-', trim($product->id_1c))[1]);
                $id_1c = trim($product->id_1c);
                $type = trim($product->type);
                $edition = intval(trim($product->edition));
                if ($type == 'edition') {
                    $id_1c = $id_1c . "-" . $edition;
                }

                $uuid = trim($product->uuid);
                $category_id = intval(trim($product->category_id_1c));
                // $category_id = intval(explode('-', trim($product->category_id_1c))[1]);

                // собираем данные
                $data = array(
                    'is_component' => intval($product->is_component),
                    'in_archive' => intval(trim($product->in_archive)),
                    'in_price' => intval(trim($product->in_priсe)),
                    // влияет только на архивные
                    'id_1c' => $id_1c,
                    'uuid' => $uuid,
                    'category_id_1c' => $category_id,
                    'type' => $type,
                    'edition' => $edition,
                    'name' => trim($product->name),
                    'content' => trim($product->content),
                    'more_about' => trim($product->more_about),
                    'vendor_code' => trim($product->vendor_code),
                    'barcode' => trim($product->barcode),
                    'codeTNVD' => trim($product->codeTNVD),
                    'country' => trim($product->country),
                    'apply' => trim($product->apply),
                    'shelf_life' => trim($product->life_time),
                    'guarantee_period' => intval($product->guarantee_period),
                    'equipment' => trim($product->equipment),
                    'weight' => trim($product->weight),
                    'netto' => trim($product->netto),
                    'width' => intval(mb_substr(trim($product->width), 0, 6)),
                    'depth' => intval(trim($product->depth)),
                    'height' => intval(trim($product->height)),
                    'packaging' => intval(trim($product->packaging)),
                    'youtube' => trim($product->video),
                    'brand' => trim($product->brand),
                    'factory' => trim($product->factory),
                    'is_new_item' => intval(trim($product->is_new_product)),
                    'date_new_item' => !empty($product->date_new_product) ? trim($product->date_new_product) : null,
                    'buy_with' => trim($product->buy_with),
                    'forget_buy' => trim($product->forget_buy),
                    'cheap_goods' => trim($product->cheap_goods),
                    'certificate' => trim($product->cert_name),
                    'certificate_exp' => !empty($product->cert_end_date) ? trim($product->cert_end_date) : null,
                    'synonyms' => trim($product->synonyms),
                    'schemes' => trim($product->schemes),
                    'importer' => intval(trim($product->importer)),
                    'adjustable' => intval(trim($product->adjustable)),
                    'spec_price' => intval(trim($product->spec_price)),
                    'analog_container_uuid' => trim($product->analogs),
                    // 'is_action' => intval(trim($this->xml_data->is_action)),
                    // 'date_open_action' => $date_open_action,
                    // 'mini_text' => trim($this->xml_data->mini_text),
                    // 'discounted' => $discounted,
                    // 'count' => intval(trim($this->xml_data->count)),
                    // 'count_type' => intval(trim($this->xml_data->count_type)),
                    // 'count_text' => trim($this->xml_data->count_text),
                );

                // имя исходного файла
                $img_input_name = trim($product->image);

                // если есть изображение, обрабатываем
                if (!empty($img_input_name)) {

                    // имя полученного большого файла
                    $img_output_name = explode('.', $img_input_name)[0] . '_' . time() . '.jpg';
                    // имя полученного среднего файла
                    $img_output_name_mid = explode('.', $img_input_name)[0] . '_mid_' . time() . '.jpg';
                    // имя полученного малого файла
                    $img_output_name_sm = explode('.', $img_input_name)[0] . '_sm_' . time() . '.jpg';

                    // проверяем, есть ли главное изображение в хранилище
                    // если нет
                    if (!file_exists($input_puth . $img_input_name)) {

                        // добавляем в дату пустые имена файлов
                        $data['image'] = "";
                        $data['image_mid'] = "";
                        $data['image_sm'] = "";

                    } else {
                        // ресайзим и переносим большое изображение
                        $this->imageHandler($img_input_name, $img_output_name, $input_puth, $output_puth, '1600', '');
                        // ресайзим и переносим малое изображение
                        $this->imageHandler($img_input_name, $img_output_name_mid, $input_puth, $output_puth, '', '170');
                        // ресайзим и переносим малое изображение
                        $this->imageHandler($img_input_name, $img_output_name_sm, $input_puth, $output_puth, '', '70');

                        // удаляем входящее изображение из хранилища
                        @unlink($input_puth . $img_input_name);

                        // добавляем в дату имена файлов
                        $data['image'] = $img_output_name;
                        $data['image_mid'] = $img_output_name_mid;
                        $data['image_sm'] = $img_output_name_sm;
                    }
                } else { // если в xml нет имени картинки

                    // добавляем в дату пустые имена файлов
                    $data['image'] = "";
                    $data['image_mid'] = "";
                    $data['image_sm'] = "";
                }

                if (isset($product->characteristics->characteristic) && $product->characteristics->characteristic->count()) {

                    // записываем
                    foreach ($product->characteristics->characteristic as $characteristic) {

                        $data_ch = array(
                            'item_1c_id' => $id_1c,
                            'characteristic_uuid' => trim($characteristic->uuid),
                            'value' => trim($characteristic->value),
                        );

                        CharacteristicItem::insert($data_ch);
                    }
                }

                // обновляем товар
                try {
                    $result = Item::create($data);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                    return new Response($e->getMessage(), 400);
                }
            }

            $response = new Response("Товары загружены!", 200);

        } else {
            $response = new Response("Нет товаров для загрузки!", 400);
        }

        return $response;
    }

    public function currency(XMLHelper $XMLHelper, Request $request)
    {

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // поля в XML, которые должны быть заполнены
        $requared_fields = [
            'id_1c',
            'rate',
        ];

        // проверка на пустоту
        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        // id_1c - 001 - курс для опта
        // id_1c - 002 - курс для МРЦ

        // берем код
        $id_1c = trim($this->xml_data->id_1c);
        // берем курс
        $rate = trim($this->xml_data->rate);

        // остальные поля в xml пока игнорируем.

        // если опт
        if ($id_1c == "001") {

            // обновляем курс для опта
            Setting::where('id', 15)->update([
                'value' => $rate,
            ]);

            // берем uuid товаров с дисконтом
            $prod_uuids = Item::where('discount_str', '!=', null)->pluck('uuid')->toArray();

            // если не пусто
            if (count($prod_uuids)) {

                foreach ($prod_uuids as $item_uuid) {

                    // обновляем информацию о дисконте
                    $this->getCommonDiscounts($item_uuid);
                }
            }

            $response = new Response("Курс для валюты {$id_1c} обновлен", 200);

        } elseif ($id_1c == "002") { // если опт
            // обновляем курс для МРЦ
            Setting::where('id', 16)->update([
                'value' => $rate,
            ]);

            $response = new Response("Курс для валюты {$id_1c} обновлен", 200);

        } else {
            $response = new Response("Некорректное значение поля id_1c!", 200);
        }

        return $response;
    }

    public function syncFiles(XMLHelper $XMLHelper, Request $request)
    {
        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если в запросе есть товары
        if (isset($this->xml_data->file) && $this->xml_data->file->count()) {

            // путь где лежат файлы
            $input_puth = '/home/alfastok/public_html/storage/ut_1c8/common/';
            // путь куда положим полученный файлы изображений
            $output_puth = '/home/alfastok/public_html/storage/ut_1c8/item-images/';
            // путь куда положим полученный файлы инструкций
            $output_puth_guides = '/home/alfastok/public_html/storage/ut_1c8/item-guides/';

            // собираем ошибочные uuid
            $error_uuid = array();

            foreach ($this->xml_data->file as $file) {

                $item_uuid = trim($file->uuid);

                // берем товар
                $item = Item::where('uuid', $item_uuid)->first(['uuid']);

                // если нет
                if (!$item) {
                    // добавляем uuid в ошибки
                    array_push($error_uuid, $item_uuid);

                    // удаляем файлы изображений из хранилища.
                    if (isset($file->images->image) && $file->images->image->count()) {

                        foreach ($file->images->image as $image) {
                            // удаляем
                            @unlink($input_puth . $image);
                        }
                    }

                    // удаляем файлы инструкций из хранилища.
                    if (isset($file->guides->guide) && $file->guides->guide->count()) {

                        foreach ($file->guides->guide as $guide) {
                            // удаляем
                            @unlink($input_puth . $guide);
                        }
                    }

                    // пропускаем итерацию
                    continue;
                }

                // если есть изображения
                if (isset($file->images->image) && $file->images->image->count()) {

                    foreach ($file->images->image as $image) {

                        // имя исходного файла
                        $img_input_name = trim($image);
                        // имя полученного большого файла
                        $img_output_name = explode('.', $img_input_name)[0] . '_' . time() . '.jpg';
                        // имя полученного среднего файла
                        $img_output_name_mid = explode('.', $img_input_name)[0] . '_mid_' . time() . '.jpg';
                        // имя полученного малого файла
                        $img_output_name_sm = explode('.', $img_input_name)[0] . '_sm_' . time() . '.jpg';

                        // если есть изображение в хранилище
                        if (file_exists($input_puth . $img_input_name)) {

                            // ресайзим и переносим изображение
                            $this->imageHandler($img_input_name, $img_output_name, $input_puth, $output_puth, '1600', '');
                            // ресайзим и переносим малое изображение
                            $this->imageHandler($img_input_name, $img_output_name_mid, $input_puth, $output_puth, '', '170');
                            // ресайзим и переносим малое изображение
                            $this->imageHandler($img_input_name, $img_output_name_sm, $input_puth, $output_puth, '', '70');

                            // собираем данные
                            $data_img = array(
                                'item_uuid' => $item_uuid,
                                'image' => $img_output_name,
                                'image_mid' => $img_output_name_mid,
                                'image_sm' => $img_output_name_sm,
                            );

                            // добавляем в БД
                            ItemImage::insert($data_img);

                            // удаляем входящее изображение из хранилища
                            @unlink($input_puth . $img_input_name);
                        }
                    }
                }

                // обрабатываем инструкции, если имеются
                if (isset($file->guides->guide) && $file->guides->guide->count()) {

                    foreach ($file->guides->guide as $guide) {

                        // если файл имеется в хранилище
                        if (file_exists($input_puth . $guide)) {

                            // собираем данные
                            $data_gu = array(
                                'item_uuid' => $item_uuid,
                                'file' => trim($guide),
                            );

                            // добавляем в БД
                            ItemGuide::insert($data_gu);

                            // перносим файл
                            rename($input_puth . $guide, $output_puth_guides . $guide);

                        }
                    }
                }
            }

            // формируем ответ
            $answer = "Пакет принят сайтом. Файлы синхронизированы успешно.";

            $err_resp = "";

            // если есть uuid, которых нет в базе
            if (count($error_uuid)) {

                // берем только уникальные
                $error_uuid = array_unique($error_uuid);

                // формируем ответ
                $answer = "Пакет принят сайтом. Не обработаны файлы для товаров: ";

                foreach ($error_uuid as $val) {
                    $answer .= "\n" . $val . " ";
                }
            }

            $response = new Response($answer, 200);

        } else {
            $response = new Response("Ошибка! Нет данных в xml.", 200);
        }

        return $response;
    }

    public function postAddPrice(XMLHelper $XMLHelper, Request $request)
    {
        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если в запросе есть товары
        if (isset($this->xml_data->products->product) && $this->xml_data->products->product->count()) {

            // собираем ошибочные uuid
            $error_uuid = array();

            foreach ($this->xml_data->products->product as $product) {

                // берем uuid товара
                $uuid = $product->uuid;

                // берем товар
                $item = Item::where('uuid', $uuid)->first();

                // если нет
                if (!$item) {
                    // добавляем uuid к ошибочным
                    array_push($error_uuid, $uuid);

                    // пропускаем итерацию
                    continue;
                } else {

                    // берем тип цен
                    $type_price = trim($product->type_price);

                    // берем значение
                    $price_val = doubleval(trim($product->price));

                    if ($type_price == "price") {
                        // обновляем цену
                        $item->update(['price_usd' => $price_val]);
                    } elseif ($type_price == "price_rub") {
                        // обновляем цену
                        $item->update(['price_rub' => $price_val]);
                    } elseif ($type_price == "price_min") {
                        // обновляем цену
                        $item->update(['price_min_usd' => $price_val]);
                    } elseif ($type_price == "price_min_rub") {
                        // обновляем цену
                        $item->update(['price_min_rub' => $price_val]);
                    } elseif ($type_price == "price_mr") {
                        // обновляем цену
                        $item->update(['price_mr_usd' => $price_val]);
                    } elseif ($type_price == "price_mr_rub") {
                        // обновляем цену
                        $item->update(['price_mr_rub' => $price_val]);
                    } elseif ($type_price == "service") {
                        // обновляем нормо-час
                        $item->update(['norm_hour' => $price_val]);
                    }

                    // обновляем информацию о дисконте у товара
                    $this->getCommonDiscounts($uuid);

                }
            }

            // формируем ответ
            $answer = "Пакет принят сайтом. Цены установлены успешно.";

            $err_resp = "";

            // если есть uuid, которых нет в базе
            if (count($error_uuid)) {

                // берем только уникальные
                $error_uuid = array_unique($error_uuid);

                // формируем ответ
                $answer = "Пакет принят сайтом. Не обработаны цены на товары: ";

                foreach ($error_uuid as $val) {
                    $answer .= "\n" . $val . " ";
                }

            }

            $response = new Response($answer, 200);

        } else {
            $response = new Response("Ошибка! Нет данных в xml.", 200);
        }

        return $response;

    }

    public function postAddCount(XMLHelper $XMLHelper, Request $request)
    {
        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если в запросе есть товары
        if (isset($this->xml_data->products->product) && $this->xml_data->products->product->count()) {

            // собираем ошибочные uuid
            $error_uuid = array();

            foreach ($this->xml_data->products->product as $product) {

                // берем uuid товара
                $uuid = $product->uuid;

                // берем товар
                $item = Item::where('uuid', $uuid)->first();

                // если нет
                if (!$item) {
                    // добавляем uuid к ошибочным
                    array_push($error_uuid, $uuid);

                    // пропускаем итерацию
                    continue;
                } else {

                    // собираем данные
                    $data = [
                        'amount' => intval(trim($product->amount)),
                        'reserve' => intval(trim($product->reserve)),
                        'locked' => intval(trim($product->locked)),
                        'expected' => intval(trim($product->expected)),
                        'expected_date' => trim($product->date),
                    ];

                    // обновляем товар
                    $item->update($data);
                }
            }

            // формируем ответ
            $answer = "Пакет принят сайтом. Количества установлены успешно.";

            $err_resp = "";

            // если есть uuid, которых нет в базе
            if (count($error_uuid)) {

                // берем только уникальные
                $error_uuid = array_unique($error_uuid);

                // формируем ответ
                $answer = "Пакет принят сайтом. Не обработаны количества для товаров: ";

                foreach ($error_uuid as $val) {
                    $answer .= "\n" . $val . " ";
                }
            }

            $response = new Response($answer, 200);

        } else {
            $response = new Response("Ошибка! Нет данных в xml.", 200);
        }

        return $response;
    }

    public function postTruncatePrice(XMLHelper $XMLHelper, Request $request)
    {

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если в запросе есть пустой тег delete
        if (isset($this->xml_data->delete) && empty(trim($this->xml_data->delete))) {

            // обнуляем значения всех цен
            Item::query()->update([
                'price_usd' => 0,
                'price_rub' => 0,
                'price_min_usd' => 0,
                'price_min_rub' => 0,
                'price_mr_usd' => 0,
                'price_mr_rub' => 0,
            ]);

            $response = new Response("Обнуление цен произведено успешно.", 200);

        } else {

            $response = new Response("Обнуление цен НЕ произведено. Тег delete отсутствует или не пустой.", 200);

        }

        return $response;
    }

    public function postTruncateAmount(XMLHelper $XMLHelper, Request $request)
    {

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если в запросе есть пустой тег delete
        if (isset($this->xml_data->delete) && empty(trim($this->xml_data->delete))) {

            // обнуляем значения всех цен
            Item::query()->update([
                'amount' => 0,
                'reserve' => 0,
                'locked' => 0,
                'expected' => 0,
                'expected_date' => null,
            ]);

            $response = new Response("Обнуление количеств произведено успешно.", 200);

        } else {

            $response = new Response("Обнуление количеств НЕ произведено. Тег delete отсутствует или не пустой.", 200);

        }

        return $response;
    }


}
