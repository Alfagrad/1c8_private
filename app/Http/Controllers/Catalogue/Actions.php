<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Traits\ActionItemsTrait;
use App\Traits\CheapItemsTrait;
use App\Traits\MenuCategory;
use Illuminate\Http\Request;

class Actions extends Controller
{

    use MenuCategory, ActionItemsTrait, CheapItemsTrait;

    public function __invoke(Request $request)
    {

        // собираем категории для левого меню
        $menu_categories = $this->getMenuCategory();
        $data['categories'] = $menu_categories;

        $data['category_id'] = 0;

        // собираем акционные товары и спецпредложения (из трейта)
        $items = $this->getActionItems();

        // собираем уцененные товары и добавляем к товарам
        $items = $this->getCheapItems($items);

        $data['items'] = $items;

        // для подсчета количества новых товаров
        $new_items_count = Item::where('is_new_item', 1)->where('amount', '>', 0)->get(['id']);
        $data['new_items_count'] = $new_items_count->count();

        return view('catalog.new_all_actions')->with($data);
    }

}
