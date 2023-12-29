<?php

namespace App\Http\Controllers\Api_1c8;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Helpers\XMLHelper;

use App\Models\Discount;
use App\Models\DiscountValue;
use App\Models\DiscountAgreement;
use App\Models\DiscountProduct;
use App\Models\Item;
use App\Models\Agreement;
use App\Models\AgreementProduct;


class DiscountController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function discountCreateOrUpdate(XMLHelper $XMLHelper, Request $request)
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

        // берем uuid
        $uuid = trim($this->xml_data->uuid);

        // записываем данные из xml в data
        $data = array(
            'uuid' => $uuid,
            'name' => trim($this->xml_data->name),
        );

        // если есть значения дисконтов
        if ($this->xml_data->discounts->discount && $this->xml_data->discounts->discount->count()) {

            // добавляем или обновляем дисконт
            Discount::updateOrCreate(['uuid' => $uuid], $data);

            // удаляем все значения дисконта
            DiscountValue::where('discount_uuid', $uuid)->delete();

            // записываем новые
            foreach ($this->xml_data->discounts->discount as $discount) {

                // мин количество товара, на которое действует
                $condition = intval(trim($discount->condition));
                // если 0
                if($condition == 0) {
                    // действует от 1 шт
                    $condition = 1;
                }

                // записываем данные из xml в data
                $data_d = array(
                    'discount_uuid' => $uuid,
                    'value' => -doubleval(trim($discount->value)),
                    'condition' => $condition,
                );

                // добавляем значения дисконта
                DiscountValue::create($data_d);

            }

            // смотрим не является ли дисконт общим
            $common_discount = DiscountAgreement::where([['discount_uuid', $uuid], ['agreement_uuid', '']])->first(['id']);

            // если общий
            if ($common_discount) {

                // берем uuid товаров дисконта
                $product_uuids = DiscountProduct::where('discount_uuid', $uuid)->pluck('product_uuid')->toArray();

                // если не пусто
                if (count($product_uuids)) {

                    foreach ($product_uuids as $item_uuid) {

                        // обновляем информацию о дисконте у товаров
                        $this->getCommonDiscounts($item_uuid);

                    }
                }
            }

            $response = new Response("Дисконт создан (обновлен) успешно.", 200);

        } else {
            $response = new Response("Дисконт НЕ создан. Отсутствуют значения дисконта", 200);
        }

        return $response;
    }

    public function agreementCreateOrUpdate(XMLHelper $XMLHelper, Request $request)
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
            // 'agreement',
        ];

        // проверка на пустоту
        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        // берем uuid дисконта
        $discount_uuid = trim($this->xml_data->uuid);

        // берем uuid соглашения
        $agreement_uuid = trim($this->xml_data->agreement);

        // если есть дата начала
        if(trim($this->xml_data->date)) {

            $data['date_start'] = trim($this->xml_data->date);
            $data['date_end'] = '0000-00-00';

        }

        // если есть дата окончания
        if(trim($this->xml_data->date_end)) {

            $data['date_end'] = trim($this->xml_data->date_end);
        }

        // добавляем или обновляем дисконт
        DiscountAgreement::updateOrCreate([
            'discount_uuid' => $discount_uuid,
            'agreement_uuid' => $agreement_uuid,
        ], $data);

        $answer = "Соглашение {$agreement_uuid} для дисконта {$discount_uuid} создано (обновлено).";

        // проверяем, есть ли дисконт
        $discount = Discount::where('uuid', $discount_uuid)->first(['id']);

        // если нет
        if (!$discount) {
            // дописываем
            $answer .= " Дисконт в БД отсутствует!";
        }

        // смотрим не является ли общим
        $common_discount = DiscountAgreement::where([['discount_uuid', $discount_uuid], ['agreement_uuid', '']])->first(['id']);

        // если общий
        if ($common_discount) {

            // берем uuid товаров дисконта
            $product_uuids = DiscountProduct::where('discount_uuid', $discount_uuid)->pluck('product_uuid')->toArray();

            // если не пусто
            if (count($product_uuids)) {

                foreach ($product_uuids as $item_uuid) {

                    // обновляем информацию о дисконте у товаров
                    $this->getCommonDiscounts($item_uuid);

                }
            }
        }

        $response = new Response($answer, 200);

        return $response;
    }

    public function productCreateOrUpdate(XMLHelper $XMLHelper, Request $request)
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

        // берем uuid дисконта
        $discount_uuid = trim($this->xml_data->uuid);

        // проверяем, есть ли дисконт
        $discount = Discount::where('uuid', $discount_uuid)->first(['id']);

        // строка ответа
        $discount_answer = "";

        // если нет
        if (!$discount) {
            // дописываем ответ
            $discount_answer .= " Дисконт в БД отсутствует!";
        }

        // строка ответа
        $product_answer = "";
        // массив не существующих товаров
        $no_item = [];

        // если есть товары
        if ($this->xml_data->products->product && $this->xml_data->products->product->count()) {

            // собираем uuid товаров
            $product_uuids = [];

            foreach ($this->xml_data->products->product as $product) {

                // берем uuid товара
                $product_uuid = $product->uuid;

                // если есть дата начала
                if (trim($product->date)) {

                    // значение даты
                    $date_val = trim($product->date);

                    // имя поля
                    $date_field = "date_start";

                }

                // если есть дата окончания
                if (trim($product->date_end)) {

                    // значение даты
                    $date_val = trim($product->date_end);

                    // имя поля
                    $date_field = "date_end";

                }

                // если есть дата начала или конца
                if (trim($product->date) || trim($product->date_end)) {

                    // берем товар
                    $item = Item::where('uuid', $product->uuid)->first(['uuid']);

                    // если есть
                    if ($item) {

                        // добавляем uuid в массив
                        $product_uuids[] = $item->uuid;

                    } else {

                        $no_item[] = "\n".$product->uuid;

                    }

                    // пишем в БД
                    DiscountProduct::updateOrCreate([
                        'discount_uuid' => $discount_uuid,
                        'product_uuid' => $product->uuid,
                    ], [
                        $date_field => $date_val,
                    ]);

                }
            }

        } else {

            // дописываем ответ
            $discount_answer .= " Нет товаров в пакете!";

        }

        // если есть отсутствующие в БД товары
        if (count($no_item)) {

            // берем только уникальные
            $no_item = array_unique($no_item);

            // формируем строку
            $no_item_answer = implode("", $no_item);

            // дописываем ответ
            $discount_answer .= " Нет товаров в БД:".$no_item_answer;
        }

        // если не пусто
        if (count($product_uuids)) {

            foreach ($product_uuids as $item_uuid) {

                // обновляем информацию о дисконте
                $this->getCommonDiscounts($item_uuid);

            }

        }

        $response = new Response("Пакет принят сайтом.".$discount_answer, 200);

        return $response;
    }

    public function syncDiscounts(XMLHelper $XMLHelper, Request $request)
    {

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если пакет не будет определен
        $response = new Response("Ошибка! Пакет не определен.", 200);

        // если синхронизируем дисконты
        if ($this->xml_data->getName() == 'discounts') {

            // если в запросе есть дисконты
            if ($this->xml_data->discount->count()) {

                // очищаем таблицы
                Discount::truncate();
                DiscountValue::truncate();

                foreach ($this->xml_data->discount as $value) {

                    // берем uuid
                    $uuid = trim($value->uuid);

                    // записываем данные из xml в data
                    $data = array(
                        'uuid' => $uuid,
                        'name' => trim($value->name),
                    );

                    // если есть значения дисконтов
                    if ($value->discounts->discount && $value->discounts->discount->count()) {

                        // добавляем или обновляем дисконт
                        Discount::create($data);

                        // записываем новые
                        foreach ($value->discounts->discount as $discount) {

                            // мин количество товара, на которое действует
                            $condition = intval(trim($discount->condition));
                            // если 0
                            if($condition == 0) {
                                // действует от 1 шт
                                $condition = 1;
                            }

                            // записываем данные из xml в data
                            $data_d = array(
                                'discount_uuid' => $uuid,
                                'value' => doubleval(trim($discount->value)),
                                'condition' => $condition,
                            );

                            // добавляем значения дисконта
                            DiscountValue::create($data_d);

                        }
                    }
                }

                $response = new Response("Дисконты записаны успешно.", 200);
            } else {
                $response = new Response("Ошибка! В пакете дисконты отсутствуют.", 200);
            }
        }

        // если синхронизируем соглашения дисконтов
        if ($this->xml_data->getName() == 'agreements') {

            // если в запросе есть дисконты
            if ($this->xml_data->agreement->count()) {

                // очищаем таблицу
                DiscountAgreement::truncate();

                foreach ($this->xml_data->agreement as $value) {

                    // берем uuid дисконта
                    $data['discount_uuid'] = trim($value->uuid);
                    // берем uuid соглашения
                    $data['agreement_uuid'] = trim($value->agreement);
                    // берем даты
                    $data['date_start'] = trim($value->date);
                    $data['date_end'] = trim($value->date_end);

                    // добавляем дисконт
                    DiscountAgreement::create($data);
                }

                $response = new Response("Соглашения для дисконтов записаны успешно.", 200);
            } else {
                $response = new Response("Ошибка! В пакете соглашения дисконтов отсутствуют.", 200);
            }
        }

        // если синхронизируем товары дисконтов
        if ($this->xml_data->getName() == 'products') {

            // если в запросе есть дисконты
            if ($this->xml_data->product->count()) {

                // очищаем таблицу
                DiscountProduct::truncate();

                // формируем ответ
                $answer = '';

                foreach ($this->xml_data->product as $value) {

                    // берем uuid дисконта
                    $data['discount_uuid'] = trim($value->uuid);

                    // если есть товары
                    if ($value->products->product && $value->products->product->count()) {

                        // берем данные товара
                        $product = $value->products->product[0];

                        // берем uuid товара
                        $data['product_uuid'] = trim($product->uuid);

                        // проверяем, есть ли в товар в БД
                        $item = Item::where('uuid', $data['product_uuid'])->first(['uuid']);

                        // если нет
                        if (!$item) {
                            // добавляем в ответ
                            $answer .= "Товар ".$data['product_uuid']." отсутствует в БД. Пропущен.\n";

                            // пропускаем
                            continue;

                        } else {

                            // берем дату старта
                            $data['date_start'] = trim($product->date);

                            // берем дату окончания
                            $data['date_end'] = trim($product->date_end);

                            // если просрочена
                            if (!empty($data['date_end']) && strtotime($data['date_end']) < time()) {

                                // добавляем в ответ
                                $answer .= "Дисконт для товара ".$data['product_uuid']." просрочен. Пропущен.\n";

                                // пропускаем
                                continue;
                            } else {

                                // пишем в БД
                                DiscountProduct::create($data);

                            }
                        }
                    }
                }

                // переписываем строки общих дисконтов
                $this->allCommonDiscountStrings();

                // если ответ не пустой
                if ($answer) {
                    $answer = "Пакет принят. Есть ошибки:\n".$answer;
                } else {
                    $answer = "Пакет принят, товары дисконтов записаны.";
                }

                $response = new Response($answer, 200);
            } else {
                $response = new Response("Ошибка! В пакете товары дисконтов отсутствуют.", 200);
            }
        }

        return $response;
    }

    // для ежедневного удаления и обновления через Command
    static public function discountRetype()
    {
        // удаляем просроченые соглашения дисконтов
        DiscountAgreement::where([
            ['date_end', '!=', '0000-00-00'],
            ['date_end', '<', date('Y-m-d', time())]
        ])->delete();

        // удаляем просроченые товары дисконтов
        DiscountProduct::where([
            ['date_end', '!=', '0000-00-00'],
            ['date_end', '<', date('Y-m-d', time())]
        ])->delete();

        // собираем uuid просроченых соглашений
        $agr_uuids = Agreement::where([
            ['date_end', '!=', '0000-00-00'],
            ['date_end', '<', date('Y-m-d', time())]
        ])->pluck('uuid')->toArray();

        // удаляем товары просроченых соглашений
        AgreementProduct::whereIn('agreement_uuid', $agr_uuids)->delete();

        // удаляем просроченые соглашения
        Agreement::where([
            ['date_end', '!=', '0000-00-00'],
            ['date_end', '<', date('Y-m-d', time())]
        ])->delete();

        // переписываем строки общих дисконтов
        self::allCommonDiscountStrings();
    }

}
