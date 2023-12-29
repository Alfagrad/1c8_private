<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Traits\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class Search extends Controller
{

    use MenuCategory;

    public function __invoke(Request $request)
    {
        // $data = $this->getUserData($request);

        // собираем категории для левого меню
        $menu_categories = $this->getMenuCategory();
        $data['categories'] = $menu_categories;

        $data['category_id'] = 0;

        // поисковый запрос
        $searchKeyword = $request->search_keywords;
        $data['search_words'] = $searchKeyword;
        // делим на слова
        $searchKeywords = explode(' ', $searchKeyword);

        // собираем товар
        if (count($searchKeywords) == 1) {

            if ($request->type === 'products') {
                $items = Item::where([
                    ['id_1c', '!=', 0],
                    ['is_component', '!=', 2],
                    ['category_id_1c', '>', 0],
                    ['name', 'like', '%' . $searchKeywords[0] . '%'],
                ])
                    ->orWhere([
                        ['id_1c', '!=', 0],
                        ['is_component', '!=', 2],
                        ['category_id_1c', '>', 0],
                        ['synonyms', 'like', '%' . $searchKeywords[0] . '%'],
                    ])
                    ->orWhere('id_1c', $searchKeywords[0]); // поиск по коду
            } elseif ($request->type === 'spares') {
                $items = Item::where([
                    ['id_1c', '!=', 0],
                    ['is_component', 1],
                    ['name', 'like', '%' . $searchKeywords[0] . '%'],
                ])
                    ->orWhere([
                        ['id_1c', '!=', 0],
                        ['is_component', 1],
                        ['synonyms', 'like', '%' . $searchKeywords[0] . '%'],
                    ])
                    ->orWhere('id_1c', $searchKeywords[0]); // поиск по коду
            }

        } else {

            if ($request->type === 'products') {
                // если запрос выражение
                $qw = Item::where([['id_1c', '!=', 0], ['is_component', '!=', 2], ['category_id_1c', '>', 0]]);
            } elseif ($request->type === 'spares') {
                $qw = Item::where([['id_1c', '!=', 0], ['is_component', 1]]);
            }

            $items_name = clone $qw;
            $items_syn = clone $qw;

            foreach ($searchKeywords as $keyword) {
                $items_name->where('name', 'like', '%' . $keyword . '%');
                $items_syn->where('synonyms', 'like', '%' . $keyword . '%');
            }
            $items = $items_name->union($items_syn);
        }

        $items = $items->orderBy('name')->get();

        // dd($items);

        // Собираем коллекцию Имя раздела - Товары
        $collect = new Collection;

        // берем товар по коду
        if (count($searchKeywords) == 1) {
            $code_item = Item::where('id_1c', 'like', $searchKeywords[0] . '%')->orderBy('id_1c')->get();
            // $code_item = $items->where('id_1c', 'like', $searchKeywords[0].'%');

            if ($code_item->count()) {
                $collect->push([
                    'name' => 'Поиск по коду',
                    'items' => $code_item,
                    'is_archive_cat' => false,
                ]);
            }
        }

        // берем товар, что в наличии, не архивный
        $item_in_stock = $items->where('amount', '>', 0)
            ->where('in_archive', 0)
            ->where('id_1c', '!=', $searchKeywords[0])
            ->where('category_id_1c', '!=', '3149');
        if ($item_in_stock->count()) {
            $collect->push([
                'name' => 'Товары - в наличии',
                'items' => $item_in_stock,
                'is_archive_cat' => false,
            ]);
        }

        // берем уцененный товар, что в наличии, не архивный
        $item_discount = $items->where('amount', '>', 0)
            ->where('in_archive', 0)
            ->where('id_1c', '!=', $searchKeywords[0])
            ->where('category_id_1c', '3149');
        if ($item_discount->count()) {
            $collect->push([
                'name' => 'Уцененные товары',
                'items' => $item_discount,
                'is_archive_cat' => false,
            ]);
        }

        // берем товар, что нет наличии, не архивный
        $item_out_stock = $items->where('amount', '<=', 0)
            ->where('in_archive', 0)
            ->where('id_1c', '!=', $searchKeywords[0])
            ->where('category_id_1c', '!=', '3149');
        if ($item_out_stock->count()) {
            $collect->push([
                'name' => 'Товары - нет наличии',
                'items' => $item_out_stock,
                'is_archive_cat' => false,
            ]);
        }

        // берем архивный товар
        $item_in_archive = $items->where('in_archive', 1)->where('id_1c', '!=', $searchKeywords[0]);
        if ($item_in_archive->count()) {
            $collect->push([
                'name' => 'Товары - архивные',
                'items' => $item_in_archive->where('amount', '>', 0)->merge($item_in_archive->where('amount', '<=', 0)),
                'is_archive_cat' => true,
            ]);
        }

        // берем товар по артикулу
        $item_article = Item::where('vendor_code', 'like', '%' . $searchKeywords[0] . '%')->orderBy('name')->get();
        if ($item_article->count()) {
            $collect->push([
                'name' => 'Поиск по артикулу',
                'items' => $item_article,
                'is_archive_cat' => false,
            ]);
        }

        $data['search_result'] = $collect;

        // для подсчета количества новых товаров
        $new_items_count = Item::where('is_new_item', 1)->where('amount', '>', 0)->get(['id']);
        $data['new_items_count'] = $new_items_count->count();

        // // собираем уцененные товары
        // $cheap_items = Item::where([['category_id_1c', '3149'], ['count', '>', 0]])->get();
        // $data['cheap_items'] = $cheap_items;

        // передаем тип поиска
        $data['search_type'] = $request->type;

        return view('catalog.new_search_catalog')->with($data);
    }

}
