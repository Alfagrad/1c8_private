<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Traits\CheapItemsTrait;
use App\Traits\MenuCategory;
use Illuminate\Http\Request;

class NewItems extends Controller
{

    use MenuCategory, CheapItemsTrait;

    public function __invoke(Request $request)
    {
        // // данные юзера
        // $data = $this->getUserData($request);

        // собираем категории для левого меню
        $menu_categories = $this->getMenuCategory();
        $data['categories'] = $menu_categories;
        $data['category_id'] = 0;

        // собираем товары
        $items = Item::where('is_new_item', 1)->where('amount', '>', 0)->orderBy('date_new_item', 'desc')->get();

        // собираем уцененные товары и добавляем к товарам
        $items = $this->getCheapItems($items);

        $data['items'] = $items;

        // количество новых товаров
        $data['new_items_count'] = $items->count();

        return view('catalogue.new_items')->with($data);
    }

}
