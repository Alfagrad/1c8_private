<?php

namespace App\Helpers;

use App\Item;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Model;

// Вернет список полей, которые не совпадают.
// 
class CompareProfile {

    private $newProfile = [];
    private $oldProfile = [];
    private $fieldsToCompare = ['name',
         //'company_address',
         'bank_account', 'bank_name',
        //'trade_object', 'shops', 'coverage_area'
    ];

    private $differents = [];
    
    public function setOldProfile($oldProfile) {
        $this->oldProfile = $oldProfile;
    }

    public function setNewProfile($newProfile) {
        $this->newProfile = $newProfile;
    }


    public function RequestFieldsToArray(){

        foreach ($this->fieldsToCompare as $field){
            if($this->oldProfile->$field != $this->newProfile[$field]){
                $this->differents[] = $field;
            }
        }

        $contacts = $this->newProfile['contacts'];
        //$contacts = [["name" => "123456",  "phone" => "123456"]];

        if(count($contacts) != $this->oldProfile->contact->count()){
            $this->differents[] = 'contact';
        } else {
            foreach ( $this->oldProfile->contact as $oldContact) {
                $isEqual = 0;
                foreach ($contacts as $newContact) {
                    if($newContact['phone'] == $oldContact->phone and $newContact['name'] == $oldContact->name){
                        $isEqual = 1;
                        break;
                    }
                }

                if(!$isEqual){
                    $this->differents[] = 'contact';
                }
            }
        }



        $addresses = $this->newProfile['addresses'];

        if(count($addresses) != $this->oldProfile->address->count()){
            $this->differents[] = 'address';
        } else {
            foreach ( $this->oldProfile->address as $oldContact) {
                $isEqual = 0;
                foreach ($addresses as $newContact) {
                    if($newContact['address'] == $oldContact->address and $newContact['comment'] == $oldContact->comment){
                        $isEqual = 1;
                        break;
                    }
                }

                if(!$isEqual){
                    $this->differents[] = 'address';
                }
            }
        }
    

        return $this->differents;
    }




    //Устанавливает реквест
    // Устанавливаем профиль
    // P
}