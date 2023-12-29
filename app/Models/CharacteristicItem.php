<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharacteristicItem extends Model
{

    protected $table = 'characteristic_item';
    public $timestamps = false;

    protected $fillable = ['id','item_id_1c','item_uuid','characteristic_uuid','value'];

    public function charName()
    {
        return $this->hasOne('App\Models\Characteristic', 'uuid', 'characteristic_uuid');
    }

    // public function item1cId()
    // {
    //     return $this->belongsTo('App\Models\Item', 'item_1c_id', '1c_id');
    // }
}
