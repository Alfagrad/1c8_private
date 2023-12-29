<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

use App\Models\Cart;
use App\Models\Item;


class InCartItemComposer
{
    public function compose(View $view) {

        if(\Auth::check()) {
            // id профайла юзера
            $profile_id = \Auth::user()->profile->id;

            // берем товары корзины для вывода заказанного количества в каталожной выдаче
            $in_cart = Cart::where([['profile_id', $profile_id], ['cart_order_id', Null]])->pluck('count', 'item_1c_id');
            $data['in_cart'] = $in_cart;

            return $view->with($data);
        }

    }
}
