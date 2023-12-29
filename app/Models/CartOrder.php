<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CartOrder extends Model
{
    public $table = 'cart_order';
    protected $fillable = ['name', 'profile_id'];

    // Товары в корзине
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}
