<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Helpers\XMLHelper;

use App\Brand;


class BrandController extends BaseController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function getCreateOrUpdate(XMLHelper $XMLHelper)
    {

        $data['title'] = 'Добавление и обновление Бренда';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/brand/createOrUpdate';

        $data['desc'] = '<p>Описание полей:<br>
        <ul>
            <li><b>id_1c</b> - ID бренда из 1С. <b>INT, Обязательное</b></li>
            <li><b>name</b> - Имя бренда. <b>STRING, Обязательное</b></li>
            <li><b>image</b> - Лого бренда. <b>STRING, Обязательное</b></li>
        </ul>
        </p>';


        $this->xml->addChild('login', 'AlfaStockApi');
        $this->xml->addChild('password', 'PC6wpCjZ');

        $this->xml->addChild('id_1c', '1');
        $this->xml->addChild('name', 'KATANA');
        $this->xml->addChild('image', 'KATANA.png');

        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);

    }

    public function postCreateOrUpdate(XMLHelper $XMLHelper, Request $request)
    {

        // Поля которые должны прийти в XML
        $requared_fields = ['id_1c', 'name', 'image'];

        // Получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        if ($response = $this->xml_auth($this->xml_data, $this->xml)) {
            return $response;
        };

        // Проверка на пустоту
        $this->valid_empty($requared_fields);

        // 1с код бренда
        $brand_1c_id = trim($this->xml_data->id_1c);

        // Должно соответствовать названию полей в базе
        $data = array(
            'name' => trim($this->xml_data->name),
            'brand_1c_id' => $brand_1c_id,
            'image' => trim($this->xml_data->image),
        );

        if (!$this->has_error) {
            Brand::updateOrCreate(['brand_1c_id' => $brand_1c_id], $data);
        }

        if (!$this->has_error) {
            $this->xml->addChild('success', "Record with brand_1c_id - {$brand_1c_id} successfully added (updated)!");
        }

        $response = new Response($this->xml->asXML(), 200);

        return $response;
    }

    function valid_empty($req_fields)
    {
        foreach ($req_fields as $f) {
            if (trim($this->xml_data->{$f}) == '') {
                $this->xml->addChild('error', "Field {$f} cannot be empty!");
                $this->has_error = true;
            }
        }
    }




}
