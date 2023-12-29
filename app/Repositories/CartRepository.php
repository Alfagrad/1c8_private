<?php

namespace App\Repositories;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CartRepository
{

    public function all(): Collection
    {
        return $this->query()->get();
    }

    public function byCartOrder(?int $cartOrderId = null): Collection
    {
        return $this->query()->where('cart_order_id', $cartOrderId)->get();
    }

    private function query(): Builder
    {
        return Cart::my()->with('item');
    }

}
