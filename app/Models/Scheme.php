<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scheme extends Model
{
    protected $guarded = [];

    protected $fillable = ['uuid','name','image'];

    // public function getItem()
    // {
    //     return $this->belongsTo('App\Models\Item', 'item_id', '1c_id');
    // }

    // public function getSchemeParts()
    // {
    //     return $this->hasMany('App\Models\SchemeParts', 'scheme_id', 'scheme_id');
    // }
}
