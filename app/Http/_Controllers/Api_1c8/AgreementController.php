<?php

namespace App\Http\Controllers\Api_1c8;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Helpers\XMLHelper;

use App\Models\Agreement;
use App\Models\AgreementProduct;

class AgreementController extends BaseController
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
            'uuid',
            'name',
            'formula'
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
            'partner' => trim($this->xml_data->partner),
            'unp' => trim($this->xml_data->unp),
            'date' => trim($this->xml_data->date),
            'date_end' => trim($this->xml_data->date_end),
        );

        // обрабатываем формулу
        $formula = intval(str_replace("ДилерBYN", "", trim($this->xml_data->formula)));
        $data['formula'] = $formula;

        Agreement::updateOrCreate(['uuid' => $uuid], $data);

        // если есть товары
        if (isset($this->xml_data->products->product) && $this->xml_data->products->product->count()) {

            // берем товары по текущему соглашению
            $prods = AgreementProduct::where('agreement_uuid', $uuid);

            // если есть
            if ($prods->get()->count()) {
                // удаляем
                $prods->delete();
            }

            foreach ($this->xml_data->products->product as $product) {

                // берем uuid товара
                $prod_uuid = trim($product->uuid);

                // берем значение цены
                $price = doubleval(trim($product->price));

                // берем формулу
                $formula = trim($product->formula);

                // если нет uuid или price, пропускаем итерацию
                if (!$prod_uuid || !($price || $formula)) {
                    continue;
                }

                // собираем данные
                $data_prod = array(
                    'agreement_uuid' => $uuid,
                    'product_uuid' => $prod_uuid,
                    'price_rub' => $price,
                );

                // обрабатываем формулу
                $formula = intval(str_replace("ДилерBYN", "", $formula));
                $data_prod['formula'] = $formula;

                // записываем в БД
                AgreementProduct::insert($data_prod);
            }
        }

        $response = new Response("Соглашение {$uuid} добавлено(обновлено) успешно!", 200);

        return $response;

    }

    public function deleteAgreement(XMLHelper $XMLHelper, Request $request)
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

        // берем соглашение
        $agreement = Agreement::where('uuid', $uuid)->first();

        // если нет
        if (!$agreement) {

            $response = new Response("Соглашения {$uuid} НЕ существует!", 200);

        } else {

            // берем товары по соглашению
            $prods = AgreementProduct::where('agreement_uuid', $uuid);

            // если есть
            if ($prods->get()->count()) {
                // удаляем
                $prods->delete();
            }

            // удаляем соглашение
            $agreement->delete();

            $response = new Response("Соглашение {$uuid} удалено!", 200);
        }

        return $response;
    }

}
