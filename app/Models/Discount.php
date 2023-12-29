<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
    protected $guarded = [];

    protected $fillable = ['uuid', 'name'];

    public function prod_uuid() {
        return $this->hasMany('App\Models\DiscountProduct', 'discount_uuid', 'uuid');
    }

    public function values(): HasMany
    {
        return $this->hasMany(DiscountValue::class, 'discount_uuid', 'uuid');
    }
}
