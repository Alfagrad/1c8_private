<?php

namespace App\Repositories;

use App\Models\CartOrder;
use Illuminate\Support\Collection;

class CartOrderRepository
{

    public function all(): Collection
    {
        return auth()->user()->cartOrders;
//        $cartOrders = collect([
//            (new CartOrder())->fill([
//                'name' => 'Основная',
//            ])
//        ])->merge(auth()->user()->cartOrders);
//        return $cartOrders;
    }

    public function findOrFail(int $id): CartOrder

    {
        return CartOrder::findOrFail($id);
    }

}
