<?php

namespace App\Http\Controllers\Api_1c8;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Helpers\XMLHelper;
use Illuminate\Filesystem\Filesystem;

use App\Models\Partner;
use Illuminate\Support\Facades\Log;

class PartnerController extends BaseController
{

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

        // берем uuid
        $uuid = trim($this->xml_data->uuid);

        // берем изображение
        $image = trim($this->xml_data->logo);

        // записываем данные из xml в data
        $data = array(
            'uuid' => $uuid,
            'parent_uuid' => trim($this->xml_data->parent_uuid),
            'name' => trim($this->xml_data->name),
            'address' => trim($this->xml_data->address),
            'warehouse' => trim($this->xml_data->warehouse),
            'manager' => trim($this->xml_data->manager),
            'phone' => trim($this->xml_data->phone),
            'site' => trim($this->xml_data->site),
            'latitude' => doubleval(trim($this->xml_data->latitude)),
            'longitude' => doubleval(trim($this->xml_data->longitude)),
            'brands' => trim($this->xml_data->brands),
            'site' => trim($this->xml_data->site),
            'deleted' => 0,
        );

        // путь где лежит изображение
        $input_puth = config('ut.images_input_path');
        // путь куда положим полученный файл
        $output_puth = config('ut.images_partners_logos_path');

        // если есть изображение, обрабатываем
        if(!empty($image)) {

            // проверяем, есть ли изображение в хранилище
            // если нет
            if(!file_exists($input_puth.$image)) {

                // добавляем в дату пустое имя файла
                $data['logo'] = "";

            } else {

                // переносим изображение
                rename($input_puth.$image, $output_puth.$image);

                // добавляем в дату имя файла
                $data['logo'] = $image;

            }

        } else { // если в xml нет имени картинки

            // добавляем в дату пустое имя файла
            $data['logo'] = "";
        }

        // добавляем или обновляем
        $result = Partner::updateOrCreate(['uuid' => $uuid], $data);

        if($result) {
            $response = new Response("Партнер {$uuid} добавлен(обновлен) успешно!", 200);
        } else {
            $response = new Response("Ошибка! Партнер {$uuid} НЕ добавлен! Повторите отправку!", 200);
        }

        return $response;

    }

    public function postDelete(XMLHelper $XMLHelper, Request $request)
    {

        // пример xml
        $xml ='
<?xml version="1.0" encoding="UTF-8"?>
<data  version="1.0">
<uuid>6f3fbdae-1467-11ec-9891-005056a0b35f</uuid>
</data>
        ';

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

        // ищем партнера
        $partner = Partner::where('uuid', $uuid)->first();

        // если есть
        if($partner) {

            // помечаем на удаление
            $partner->update([
                'deleted' => 1,
            ]);

            $response = new Response("Партнер {$uuid} помечен как удаленный!", 200);
        } else {
            $response = new Response("Ошибка! Партнер {$uuid} НЕ существует!", 200);
        }

        return $response;

    }

    public function syncPartners(XMLHelper $XMLHelper, Request $request)
    {
        $answer = $this->logStart(__METHOD__);
        $this->xml_data = $XMLHelper->get_xml($request);

        if ($response = $this->xml_auth($request)) {
            return $response;
        }

//        $requared_fields = [
//            'uuid',
//        ];

//        if ($response = $this->valid_empty($requared_fields)) {
//            return $response;
//        }

        Partner::query()->delete();

        if($this->xml_data->partner->count() <= 0){
            return new Response("Нет партнеров", 400);
        }

        $answer .= $this->logLoopStarts($answer);
        $partners = [];
        foreach ($this->xml_data->partner as $partner) {
            Partner::create($this->prepareData($partner));
//            $partners[] = array_filter((array)$partner);
        }
        $answer .= $this->logCount($answer, $partners);
//        $chunked = array_chunk($partners, 100);
//        foreach ($chunked as $pack){
//            Partner::insert($pack);
//        }

//        Partner::insert($partners);

        // берем uuid
//        $uuid = trim($this->xml_data->uuid);
//
//        // ищем партнера
//        $partner = Partner::where('uuid', $uuid)->first();
//
//        // если есть
//        if($partner) {
//
//            // помечаем на удаление
//            $partner->update([
//                'deleted' => 1,
//            ]);
//
//            $response = new Response("Партнер {$uuid} помечен как удаленный!", 200);
//        } else {
//            $response = new Response("Ошибка! Партнер {$uuid} НЕ существует!", 200);
//        }
//
        $answer .= $this->logLoopEnds($answer);
        return new Response("Партнеры синхронизированы. ".$answer, 200);

    }

}
