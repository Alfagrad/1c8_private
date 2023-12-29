<?php

namespace App\Http\Controllers\Catalogue;

use App\Cart;
use App\Http\Controllers\Controller;
use App\Item;
use App\Traits\MenuCategory;
use Illuminate\Http\Request;

class Season extends Controller
{

    use MenuCategory;

    public function __invoke(Request $request)
    {
        // данные юзера
//        $data = $this->getUserData($request);

        // собираем категории для левого меню
        $data = $this->getMenuCategory();

        $data['category_id'] = 0;

        // собираем товары
        $items = Item::where('is_season', 1)->where('count', '>', 0)->orderBy('name')->get();
        $data['items'] = $items;

        // собираем уцененные товары
        $cheap_items = Item::where([['1c_category_id', '3149'], ['count', '>', 0]])->get();
        $data['cheap_items'] = $cheap_items;

        // наценка/скидка markup. Учитывается в отображении цен на сайте
        $data['data_markup'] = $data['generalProfile']->markup;

        // для вывода заказанного количества товара
        $idToCart = Cart::where([['profile_id', $data['generalProfile']->id], ['cart_order_id', Null]])->pluck('count', 'item_1c_id');
        $data['in_cart'] = $idToCart;

        // собираем количества для линков перед категориями
        // $data = array_merge($data, $this->getLinkCounts());

        return view('catalog.season_items')->with($data);
    }

}
