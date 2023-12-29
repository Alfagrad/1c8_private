<?php

namespace App\Actions\Cart;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class Swap
{

    public function __invoke(int $targetCartOrderId): int
    {
        $carts = auth()->user()->profile->carts()->whereNull('cart_order_id')->orWhere('cart_order_id', $targetCartOrderId)->get();
        return DB::transaction(function() use ($carts, $targetCartOrderId){
            return
                $this->swapCart($carts, null, $targetCartOrderId)
                +
                $this->swapCart($carts, $targetCartOrderId, null);
        });
    }

    private function swapCart(Collection $carts, ?int $sourceCartOrderId, ?int $targetCartOrderId): int
    {
        $ids = $carts->where('cart_order_id', $sourceCartOrderId)->pluck('id');
        return auth()->user()->profile->carts()->whereIn('id', $ids)->update(['cart_order_id' => $targetCartOrderId]);
    }

}
