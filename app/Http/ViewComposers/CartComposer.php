<?php

namespace App\Http\ViewComposers;

use App\Models\Cart;
use Illuminate\View\View;

class CartComposer
{

    public $carts;

    public function __construct()
    {
        $this->setCarts();
    }

    public function compose(View $view)
    {

        return $view->with('carts', $this->carts);
//        return $view->with('cart_item_line', request()->route()->getPrefix() == '/cart-page');
    }

    private function setCarts(): void
    {
        $this->carts = collect();
        if(auth()->check()){
            $this->carts = Cart::where('profile_id', profile()->id)->where('cart_order_id', null)->with('item')->get()->keyBy('item_1c_id');
        }
    }

}
