<?php

namespace App\Actions\Cart;

use Illuminate\Support\Facades\DB;

class Relocate
{

    public function copy(array $selectedCarts, ?int $sourceCartOrderId, ?int $targetCartOrderId): int
    {
        $relocatedCount = $this->relocate($selectedCarts, $sourceCartOrderId, $targetCartOrderId);

        DB::commit();

        return $relocatedCount;
    }

    public function move(array $selectedCarts, ?int $sourceCartOrderId, ?int $targetCartOrderId): int
    {
        $relocatedCount = $this->relocate($selectedCarts, $sourceCartOrderId, $targetCartOrderId);

        profile()->carts()->whereIn('id', $selectedCarts)->delete();

        DB::commit();

        return $relocatedCount;

    }

    private function relocate(array $selectedCarts, ?int $sourceCartOrderId, ?int $targetCartOrderId): int
    {
        if(count($selectedCarts) <= 0){
            return 0;
        }

        $carts = profile()->carts;

        $relocatedCount = 0;

        DB::beginTransaction();

        // Выбранные товары, текущая корзина
        foreach ($carts->where('cart_order_id', $sourceCartOrderId)->whereIn('id', $selectedCarts) as $cart){
            // Товар в целевой корзине
            $targetCart = $carts->where('cart_order_id', $targetCartOrderId)->where('item_1c_id', $cart->item_1c_id)->first();

            // Увеличиваем количество, если товар найден
            $relocatedCart = profile()->carts()->updateOrCreate(['item_1c_id' => $cart->item_1c_id, 'cart_order_id' => $targetCartOrderId], ['count' => $cart->count + ($targetCart?->count ?? 0)]);

            $relocatedCount = $relocatedCount + (int)$relocatedCart->exists;
        }

        return $relocatedCount;
    }

}
