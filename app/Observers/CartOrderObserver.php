<?php

namespace App\Observers;

use App\Models\CartOrder;

class CartOrderObserver
{
    /**
     * Handle the CartOrder "created" event.
     */
    public function created(CartOrder $cartOrder): void
    {
        //
    }

    /**
     * Handle the CartOrder "updated" event.
     */
    public function updated(CartOrder $cartOrder): void
    {
        //
    }

    public function deleting(CartOrder $cartOrder): void
    {
        $cartOrder->carts()->delete();
    }

    /**
     * Handle the CartOrder "deleted" event.
     */
    public function deleted(CartOrder $cartOrder): void
    {
        //
    }

    /**
     * Handle the CartOrder "restored" event.
     */
    public function restored(CartOrder $cartOrder): void
    {
        //
    }

    /**
     * Handle the CartOrder "force deleted" event.
     */
    public function forceDeleted(CartOrder $cartOrder): void
    {
        //
    }
}
