<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Characteristic extends Model
{
    protected $guarded = [];

    protected $fillable = ['uuid','name'];

    // public function categoryId()
    // {
    //     return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    // }
}
