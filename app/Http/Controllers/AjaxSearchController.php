<?php

namespace App\Http\Controllers;

use App\Helpers\ImageResize;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Profile;

class AjaxSearchController extends Controller
{
    function ajaxSearch(Request $request)
    {
        // все данные по юзеру
        // $data = $this->getUserData($request);

        // поисковый запрос
        $searchKeyword = $request->search_keywords;
        $data['searchKeyword'] = $searchKeyword;

        // делим на слова
        $searchKeywords = explode(' ', $searchKeyword);
        $data['searchKeywords'] = $searchKeywords;

        // собираем категории
        $categories = Category::where('id_1c', '!=', 20070)->where('parent_1c_id', '!=', 20070); // исключаем услуги

        foreach ($searchKeywords as $keyword) {
            $categories->where('name', 'like', '%' . $keyword . '%');

        }

        $categories = $categories->orderBy('name')->take(12)->get(['id_1c', 'name', 'image']);
        $data['categories'] = $categories;

        // собираем товар
        if(count($searchKeywords) == 1) {

            if($request->type === 'products') {
                $items = Item::where([
                        ['id_1c', '!=', 0],
                        ['is_component', '!=', 2],
                        ['category_id_1c', '>', 0],
                        ['name', 'like', '%'.$searchKeywords[0].'%'],
                    ])
                    ->orWhere([
                        ['id_1c', '!=', 0],
                        ['is_component', '!=', 2],
                        ['category_id_1c', '>', 0],
                        ['synonyms', 'like', '%'.$searchKeywords[0].'%'],
                    ])
                    ->orWhere('id_1c', 'like', $searchKeywords[0].'%'); // поиск по коду
            } elseif($request->type === 'spares') {
                $items = Item::where([
                        ['id_1c', '!=', 0],
                        ['is_component', 1],
                        ['name', 'like', '%'.$searchKeywords[0].'%'],
                    ])
                    ->orWhere([
                        ['id_1c', '!=', 0],
                        ['is_component', 1],
                        ['synonyms', 'like', '%'.$searchKeywords[0].'%'],
                    ])
                    ->orWhere('id_1c', 'like', $searchKeywords[0].'%'); // поиск по коду
            }

        } else {

            if($request->type === 'products') {
                // если запрос выражение
                $qw = Item::where([['id_1c', '!=', 0], ['is_component', '!=', 2], ['category_id_1c', '>',0]]);
            } elseif($request->type === 'spares') {
                $qw = Item::where([['id_1c', '!=', 0], ['is_component', 1]]);
            }

            $items_name = clone $qw;
            $items_syn = clone $qw;

            foreach ($searchKeywords as $keyword) {
                $items_name->where('name', 'like', '%'.$keyword.'%');
                $items_syn->where('synonyms', 'like', '%'.$keyword.'%');
            }
            $items = $items_name->union($items_syn);
        }

        // берем товар по коду
        if(count($searchKeywords) == 1) {
            $itemCode = $items->where('id_1c', 'like', $searchKeywords[0].'%')->get();
        } else {
            $itemCode = '';
        }
        $data['itemCode'] = $itemCode;

        // берем товар по артикулу
        $itemArticle = Item::where('vendor_code', 'like', '%'.$searchKeywords[0].'%');
        $data['item_article_count'] = $itemArticle->count();
        $data['itemArticle'] = $itemArticle->orderBy('name')->take(3)->get();

        $items = $items->orderBy('name')->get();

        // берем товар, что в наличии, не архивный
        $items_yes = $items
            ->where('amount', '>', 0)
            ->where('in_archive', 0)
            ->where('category_id_1c', '!=', '3149')
            ->sortByDesc('price_rub');
        $data['items_yes_count'] = $items_yes->count();
        $items_yes = $items_yes->take(12);
        $data['items_yes'] = $items_yes;

        // берем уцененный товар, что в наличии, не архивный
        $items_yes_low_cost = $items
            ->where('amount', '>', 0)
            ->where('in_archive', 0)
            ->where('category_id_1c', '3149')
            ->sortByDesc('price_rub');
        $data['items_yes_low_cost_count'] = $items_yes_low_cost->count();
        $items_yes_low_cost = $items_yes_low_cost->take(12);
        $data['items_yes_low_cost'] = $items_yes_low_cost;

        // берем товар, что нет наличии, не архивный
        if($data['items_yes_count'] < 12) {
            $take = 12 - $data['items_yes_count'];
            if($take < 0) {
                $take = 0;
            }
        } else {
            $take = 0;
        }
        $items_no = $items
            ->where('amount', '<=', 0)
            ->where('in_archive', 0)
            ->where('category_id_1c', '!=', '3149')
            ->sortByDesc('price_rub');
        $data['items_no_count'] = $items_no->count();
        $items_no = $items_no->take($take);
        $data['items_no'] = $items_no;

        // берем архивный товар
        $archive = $items
            ->where('in_archive', 1)
            ->sortByDesc('price_rub');
        $data['archive_count'] = $archive->count();
        $archive = $archive->take(6);
        $data['archive'] = $archive;

        // количество найденных товаров
        $itemCount = $data['items_yes_count'] + $data['items_yes_low_cost_count'] + $data['items_no_count'] + $data['archive_count'];
        $data['itemCount'] = $itemCount;

        // тип поиска
        $data['type'] = $request->type;

        // метка для сервисного центра
        $data['is_service'] = 0;
// return $data['is_service'];

        $data['imageResize'] = new ImageResize();

        return view('catalogue.search.search')->with($data);
    }

}
