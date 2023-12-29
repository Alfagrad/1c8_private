<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandCertificate extends Model
{

    protected $guarded = [];
    protected $table = 'brand_certificates';

    public function brandId(){
        return $this->belongsTo('App\Models\Brand', 'brand_id', 'id');
    }


}
