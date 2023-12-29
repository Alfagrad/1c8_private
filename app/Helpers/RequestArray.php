<?php

namespace App\Helpers;

use App\Item;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Model;


class RequestArray {

    // Получает массив из Request и переводит в нормальный массив
    //$request->contact
    public function RequestFieldsToArray($requestFields){

        if(!$requestFields){
            return [];
        }

        $changeFields = array();
        foreach (array_keys($requestFields) as $fieldKey) {
            foreach ($requestFields[$fieldKey] as $key=>$value) {
                $changeFields[$key][$fieldKey] = $value;
            }
        }
        return $changeFields;

    }
}