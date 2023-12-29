<?php

namespace App\Http\Controllers\Api_1c8;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Helpers\XMLHelper;


class TestController extends BaseController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index(XMLHelper $XMLHelper, Request $request)
    {

        // Получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        if ($response = $this->xml_auth($this->xml_data, $this->xml)) {
            return $response;
        };

        return $this->xml_data->login;
    }
}
