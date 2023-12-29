<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    protected $guarded = [];

    protected $fillable = ['item_uuid','image','image_mid','image_sm'];
}
