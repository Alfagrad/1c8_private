<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Models\CartOrder;
use App\Models\CartServiceItem;

class ServiceCartController extends Controller
{
    public function index(Request $request)
    {

        // id профайла юзера
        $profile_id = Auth::user()->profile->id;

        // корзины юзера
        $user_carts = CartOrder::where('profile_id', $profile_id)->get();

        // собираем товары в корзине
        $items = CartServiceItem::where('profile_id', $profile_id)->get();

// dd($user_carts, $items);


        return view('service_cart');
    }
}
