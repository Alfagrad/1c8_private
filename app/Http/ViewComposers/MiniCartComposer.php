<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

use App\Traits\MiniCartDataTrait;

use App\Models\Cart;
use App\Models\Item;


class MiniCartComposer
{
    use MiniCartDataTrait;

    public function compose(View $view) {

        if(\Auth::check()) {

            $mini_cart = $this->getCartData();

            return $view->with($mini_cart);
        }
    }
}
