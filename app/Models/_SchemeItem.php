<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchemeItem extends Model
{
    protected $fillable = [];
    public $timestamps = false;

    public function item()
    {
        return $this->hasOne('App\Models\Item', 'uuid', 'item_uuid');
    }

}
