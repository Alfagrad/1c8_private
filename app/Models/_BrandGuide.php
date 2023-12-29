<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandGuide extends Model
{
    protected $guarded = [];
    protected $table = 'brand_guides';

    public function brandId(){
        return $this->belongsTo('App\Models\Brand', 'brand_id', 'id');
    }

}
