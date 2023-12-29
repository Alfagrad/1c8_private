<?php

namespace App\Helpers;

use App\Models\XMLog;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;


class XMLHelper {

    // ПРиводит XML в нормальный вид с переносами
    public function beauty_xml($xml){
        $dom = new \DOMDocument("1.0");
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        return $dom->saveXML();
    }



    public function get_xml(Request $request){
        if(!$request->has('xml')){
            $xml_msg = @file_get_contents('php://input');
            $xget = new SimpleXMLElement($xml_msg);
            XMLog::create(array('text'=>$xml_msg));
        }else{
            $xget = new SimpleXMLElement($request->get('xml'));
            XMLog::create(array('text'=>$request->get('xml')));
        }

        return $xget;
    }
}
