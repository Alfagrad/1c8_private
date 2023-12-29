<?php

namespace App\Http\Controllers\Api_1c8;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\XMLHelper;

use App\Models\Characteristic;

class CharacteristicController extends BaseController
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
        ];

        // проверка на пустоту
        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }


        $data = array(
            'uuid' => trim($this->xml_data->uuid),
            'name' => trim($this->xml_data->name),
        );

        $uuid = trim($this->xml_data->uuid);

        $result = Characteristic::updateOrCreate(['uuid' => $uuid], $data);

        if($result) {
            $response = new Response("Характеристика {$uuid} добавлена(обновлена) успешно!", 200);
        } else {
            $response = new Response("Ошибка! Характеристика {$uuid} НЕ добавлена! Повторите отправку!", 200);
        }

        return $response;
    }

    public function firstUpload(XMLHelper $XMLHelper, Request $request)
    {

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если в запросе есть характеристики
        if ($this->xml_data->characteristic->count()) {

            // очищаем таблицу в БД
            Characteristic::truncate();

             // обрабатываем
            foreach ($this->xml_data->characteristic as $characteristic) {

                // записываем данные из xml в data
                $data = array(
                    'uuid' => trim($characteristic->uuid),
                    'name' => trim($characteristic->name),
                );

                // добавляем
                Characteristic::create($data);
            }

            $response = new Response("Характеристики загружены!", 200);

        } else {
            $response = new Response("Нет характеристик для загрузки!", 200);
        }

        return $response;
    }

}
