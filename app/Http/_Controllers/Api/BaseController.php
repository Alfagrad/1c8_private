<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use SimpleXMLElement;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    public $xml;
    public $xml_data;
    public $has_error = false;
    public $type_action = '';

    public $login = 'AlfaStockApi';
    public $password = 'OiTzNaZA';


    public function __construct(Request $request)
   {
       parent::__construct($request);
       $this->xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><data></data>');
       $this->xml->addAttribute('version', '1.0');

   }

    public function xml_auth($xget, $xml){
        if(('OiTzNaZA' !== (string)$xget->password) or ('AlfaStockApi' !== (string)$xget->login)){
            $xml->addChild('error', 'Password or username is not correct.');
            $response = new Response($xml->asXML(), 200);
            return $response;
        }

    }
}
