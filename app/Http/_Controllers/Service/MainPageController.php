<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Cart;
use App\Category;
use App\Feedback;
use App\Item;
use App\Profile;
use App\User;
use App\SchemeParts;
use App\Scheme;
use Carbon\Carbon;
use Mockery\Exception;


class MainPageController extends Controller
{
    public function getIndex(Request $request)
    {
// session(['current_category' => '']);
        $data = $this->getUserData($request);

        if (session('current_category')) {
            return   redirect('service/'.session('current_category'));
        }

        $categories = Category::with(['subCategory' => function ($query) {
            $query->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC');
        }])->where('parent_1c_id', 0)->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC')->get();
        $data['categories'] = $categories;
        $data['useLocalStorage'] = true;
        $data['searchCategory'] = false;

        $blockSearchCatalog =   $this->refresh($request);
        $data['blockSearchCatalog'] = $blockSearchCatalog;

    	return view('service.home_page', $data);
    }

    public function currentCategory(Request $request, $id)
    {

        session(['current_category' => $id]);
        $data = $this->getUserData($request);

        $categories = Category::with(['subCategory' => function ($query) {
            $query->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC');
        }])->where('parent_1c_id', 0)->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC')->get();
        $data['categories'] = $categories;

        $request->categoryId = $id;

        $blockSearchCatalog =   $this->refresh($request);
        $data['blockSearchCatalog'] = $blockSearchCatalog;

        $data['useLocalStorage'] = false;
        $data['cancelRefresh'] = true;
        $data['searchCategory'] = $id;

        return view('service.home_page', $data);
    }



    // Так же нужен аякс который будет отдавать разные данные
    public function refresh(Request $request)
    {


        $data = $this->getUserData($request);
        $categoryId = $request->categoryId;

        $categoriesRequest = Category::with(['items' => function ($query) {
            $query->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC');
        }, 'items.actions', 'items.images', 'items.gift', 'items.cheap_good', 'parentCategory.parentCategory', 'items.getChilds', 'items.getParent'], ['subCategory' => function ($query) {

        }]);

        $categoriesRequestDetails = Category::with(['items' => function ($query) {
            $query->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC');
        }, 'items.actions', 'items.images', 'items.gift', 'items.cheap_good', 'parentCategory.parentCategory', 'items.getChilds', 'items.getParent'], ['subCategory' => function ($query) {

        }]);

        $profile = $request->user()->profile()->with('address', 'contact', 'depts')->first();

        $Category = Category::with(['subCategory' => function ($query) {
            $query->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC');
        }])->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC');

        if (session('current_category')) {
            $Category->where('1c_id' ,session('current_category'));
        }
        else if(!session('current_category')) {
            $Category->where('1c_id' ,7024);
        }
        else if($categoryId != null) {
            $Category->where('1c_id', $categoryId);

        }

        $subCategory = $Category->first();

        if($subCategory == null) {
            $subCategory = Category::where('1c_id' ,7024)->first();
        }

        if ($subCategory->subCategory) {
            $subcategories[] = $subCategory->{'1c_id'};
            foreach ($subCategory->subCategory as $cat) {
                $subcategories[] = $cat->{'1c_id'};
                if ($cat->subCategory) {
                    foreach ($cat->subCategory as $c) {
                        $subcategories[] = $c->{'1c_id'};
                    }
                }
            }
        }

        $categoriesDetails = $categoriesRequestDetails->whereIn('1c_id', $subcategories)->orWhere('1c_id', $subCategory->{'1c_id'})->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC')->get()->keyBy('1c_id');;
        $categories = $categoriesRequest->whereIn('1c_id', $subcategories)->orWhere('1c_id', $subCategory->{'1c_id'})->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC')->get()->keyBy('1c_id');


        foreach($subcategories as $item) {

            $collection[] = $categories[$item];

            // собираем аналоги, если и товар и запчасть одновременно
            foreach($categories[$item]->items as $spare_analog) {
                if($spare_analog->is_component == '1' && $spare_analog->{'1c_category_id'} > 0) {
                    $scheme_parts = SchemeParts::where('spare_id', $spare_analog->{'1c_id'})->get();
                    $items = collect();
                    foreach ($scheme_parts as $part) {
                        $tmp = SchemeParts::where([
                            ['scheme_id', $part->scheme_id],
                            ['number_in_schema', $part->number_in_schema],
                            ['spare_id', '!=', $spare_analog->{'1c_id'}],
                        ])->get(['spare_id']);
                        $items->push($tmp);
                    }
                    $items = $items->flatten()->unique();
                    $item_analogs[$spare_analog->{'1c_id'}] = $items;
                }
            }
            // ********************************************************
        }
        if(!isset($item_analogs)) $item_analogs = '';

        $idToCart = Cart::where('profile_id', $profile->id)->pluck('count', 'item_1c_id');

        // собираем 1c_id категорий расходных материалов
        $supplies_id = [];
        $cat_supplies = Category::where('parent_1c_id', 12710)->get(['1c_id'])->pluck('1c_id')->toArray();
        $supplies_id = array_merge($supplies_id, $cat_supplies);
        foreach($cat_supplies as $sub) {
            $sub_cat_supplies = Category::where('parent_1c_id', $sub)->get(['1c_id'])->pluck('1c_id')->toArray();
            if(count($sub_cat_supplies)) {
                $supplies_id = array_merge($supplies_id, $sub_cat_supplies);
            }
        }

        return view('service.includes.refresh_catalog', [
            'categories' => $collection,
            'item_analogs' => $item_analogs,
            'categoriesDetails' => $categoriesDetails,
            'idToCart' => $idToCart,
            'currency' => $request->get('currency', 'byn'),
            'token' => Auth::user()->login_token,
            'supplies_id' => $supplies_id
        ]);
    }

    public function item(Request $request, $itemId)
    {
        $data = $this->getUserData($request);
        $profile = $request->user()->profile()->first();
        $item = Item::where('1c_id', $itemId)->first();
        $discountedItem = Item::whereIn('1c_id', explode(',', $item->cheap_goods))->get();
        $buyWith = Item::whereIn('1c_id', explode(',', $item->buy_with))->orderBy('name')->get();
        $buyWithCategory = Category::with('items')->whereIn('1c_id', explode(',', $item->buy_with))->get();
        $buyForget = Item::whereIn('1c_id', explode(',', $item->forget_buy))->get();
        $countItemInCart = Cart::where('profile_id', $profile->id)->where('item_1c_id', $item->{'1c_id'})->first();
//dd($buyWith);
        $chipItems = Item::where([['1c_category_id', '3149'], ['count', '>', 0]])->get();


        $idToCart = Cart::where('profile_id', $profile->id)->pluck('count', 'item_1c_id');

        $items_yes = false; // Товары которые есть в наличии
        $items_no = false; // Товары которых нет в наличии

        $id_1c = $item['1c_id']; // Вытягиваем 1с ид

        $scheme_parts = SchemeParts::where('spare_id', $id_1c)->get();
        $items = collect(); // Коллекция аналогов

        foreach ($scheme_parts as $key => $part) {
            // if ($key == 0) //Первое заполнение аналога
            // {
            //     $tmp = SchemeParts::where([
            //         ['scheme_id', $part->scheme_id],
            //         ['number_in_schema', $part->number_in_schema],
            //         ['spare_id', '!=', $id_1c],
            //     ])->get();
            //     $items->push($tmp);

            //     continue;
            // }

            //Объединение аналогов
            $tmp = SchemeParts::where([
                ['scheme_id', $part->scheme_id],
                ['number_in_schema', $part->number_in_schema],
                ['spare_id', '!=', $id_1c],
            ])->get(['spare_id']);
            $items->push($tmp);
        }
        $items = $items->flatten()->unique();

        $category = Category::where('1c_id', $item->{'1c_category_id'})->first();
        $breadcrumbs = null;
        if($category != null) {

            $breadcrumbs = (new Category)->getBreadcrumbs($category);
        }

        // наценка/скидка markup. Учитывается в отображении цен на сайте
        $markup = $data['generalProfile']->markup;

        // собираем 1c_id категорий расходных материалов
        $supplies_id = [];
        $cat_supplies = Category::where('parent_1c_id', 12710)->get(['1c_id'])->pluck('1c_id')->toArray();
        $supplies_id = array_merge($supplies_id, $cat_supplies);
        foreach($cat_supplies as $sub) {
            $sub_cat_supplies = Category::where('parent_1c_id', $sub)->get(['1c_id'])->pluck('1c_id')->toArray();
            if(count($sub_cat_supplies)) {
                $supplies_id = array_merge($supplies_id, $sub_cat_supplies);
            }
        }

        return view('service.item', [
            'item_card' => $item,
            'breadcrumbs' => $breadcrumbs,
            'buyWith' => $buyWith,
            'buyWithCategory' => $buyWithCategory,
            'buyForget' => $buyForget,
            'discountedItem' => $discountedItem,
            'countItemInCart' => $countItemInCart,
            'itemNameToPopUp' => $item->name,
            'item1cId' => $item->{'1c_id'},
            'token' => Auth::user()->login_token,
            'currency' => $request->get('currency', 'byn'),
            'items_yes' => $items_yes,
            'items_no' => $items_no,
            'analog' => $items->count(),
            'items' => $items->count() > 0 ? $items->unique('spare_id') : collect(),
            'chipItems' => $chipItems,
            'data_markup' => $markup,
            'supplies_id' => $supplies_id,
        ])->with($data);

    }

    public function search(Request $request)
    {

        $data = $this->getUserData($request);
        $profile = $request->user()->profile()->with('address', 'contact', 'depts')->first();

        $searchKeyword = $request->search_keywords;

        $searchKeywords = explode(' ', $searchKeyword);

        $items = Item::with('images')->where('id', '>', 0);

        // собираем аналоги, если и товар и запчасть одновременно
            // определяем у каких товаров могут быть аналоги
        $spares_analogs = clone $items;
        $spares_analogs = $spares_analogs->where([['is_component', '1'], ['1c_category_id', '>', 0]])->get(['1c_id']);
            // собираем аналоги 
        foreach($spares_analogs as $item) {
            $scheme_parts = SchemeParts::where('spare_id', $item->{'1c_id'})->get();
            $itm = collect();
            foreach ($scheme_parts as $part) {
                $tmp = SchemeParts::where([
                    ['scheme_id', $part->scheme_id],
                    ['number_in_schema', $part->number_in_schema],
                    ['spare_id', '!=', $item->{'1c_id'}],
                ])->get(['spare_id']);
                $itm->push($tmp);
            }
            $itm = $itm->flatten()->unique();
            $item_analogs[$item->{'1c_id'}] = $itm;
        }


        $archive = Item::with('images')->where([
            ['id', '>', 0],
            ['1c_category_id', 0],
            ['is_component', 0]
        ]);

        if($request->type === 'products') {
            $items = $items->where([['1c_category_id', '>',0]]);
        }
        if($request->type === 'spares') {
            $items = $items->where([['is_component', '=', 1]]);
        }

        foreach ($searchKeywords as $keyword) {
            $items->where('name', 'like', '%' . $keyword . '%');
            $archive->where('name', 'like', '%' . $keyword . '%');
        }

        $products_yes = clone $items;

        // если вводится число, поиск по коду товара
        if(count($searchKeywords) === 1 AND is_numeric($searchKeywords[0])) {
            $item_by_code = Item::with('images')->where('1c_id',  $searchKeywords[0])->first();
            if((bool)$item_by_code) $products_yes = true;
        } else {
            $item_by_code = '';
        }


        $spares_yes = clone $items;
        $spares_yes = (bool)$spares_yes->where('is_component', 1)->get()->count();

        // товары в наличии
        $items_yes = clone $items;
        $items_yes = $items_yes->where('count', '>', 0)->where('1c_category_id', '<>',  3149)->get();

        // товары нет в наличии
        $items_no = clone $items;
        $items_no = $items_no->where('count', '<=', 0)->get();

        // уцененные товары для поисковой выдачи
        $items_yes_low_cost = clone $items;
        $items_yes_low_cost = $items_yes_low_cost->where([['1c_category_id', '3149'], ['count', '>', 0]])->get();

        // уцененные товары все
        $chipItems = Item::where([['1c_category_id', '3149'], ['count', '>', 0]])->get();

        // архивные товары
        $archive = $archive->get();

        $categories = Category::with(['subCategory' => function ($query) {
            $query->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC');
        }], 'subCategory.subCategory', 'parentCategory')->where('parent_1c_id', 0)->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC')->get();


        $idToCart = Cart::where('profile_id', $profile->id)->pluck('count', 'item_1c_id');

        // собираем 1c_id категорий расходных материалов
        $supplies_id = [];
        $cat_supplies = Category::where('parent_1c_id', 12710)->get(['1c_id'])->pluck('1c_id')->toArray();
        $supplies_id = array_merge($supplies_id, $cat_supplies);
        foreach($cat_supplies as $sub) {
            $sub_cat_supplies = Category::where('parent_1c_id', $sub)->get(['1c_id'])->pluck('1c_id')->toArray();
            if(count($sub_cat_supplies)) {
                $supplies_id = array_merge($supplies_id, $sub_cat_supplies);
            }
        }

        $blockSearchCatalog = '';
        $type = $request->type ? $request->type : 'products';

        if(
            !(bool)$item_by_code
            && !(bool)$items_yes->count()
            && !(bool)$items_no->count()
            && !(bool)$archive->count()
            && !(bool)$items_yes_low_cost->count()
        ) {
            $blockSearchCatalog = view('service.includes.search_answer', compact('searchKeyword', 'type'));
        } else {
            $blockSearchCatalog = view('service.includes.search', [
                'items' => $items,
                'item_by_code' => $item_by_code,
                'items_yes' => $items_yes,
                'items_no' => $items_no,
                'type' => $type,
                'archive' => $archive,
                'items_yes_low_cost' => $items_yes_low_cost->sortBy('name'),
                'item_analogs' => $item_analogs,
                'idToCart' => $idToCart,
                'currency' => $request->get('currency', 'byn'),
                'token' => Auth::user()->login_token,
                'spares_yes' => $spares_yes,
                'products_yes' => $products_yes,
                'chipItems' => $chipItems,
                'supplies_id' => $supplies_id
            ]);
        }

        $data['useLocalStorage'] = false;
        $data['cancelRefresh'] = true;

        return view('service.home_page', [
            'categories' => $categories,
            'search' => true,
            'blockSearchCatalog' => $blockSearchCatalog,
        ])->with($data);
    }

    public function spares_search(Request $request)
    {

        $user = User::where('login_token', trim($request->token))->get()->first();
        Auth::loginUsingId($user->id);

        $data = $this->getUserData($request);

        $id = Item::find($request->id);
        $id = $id['1c_id'];

        $items = SchemeParts::where('parent_id', $id)->orderBy('scheme_no')->orderBy('number_in_schema')->get();
        $scheme = Scheme::where('item_id', $id)->get();

        $items_yes = false;//(bool)$items_yes->where('count', '>', '0')->count();
        $items_no = false;//(bool)$items_no->where('count', '=', '0')->count();

        $profile = $request->user()->profile()->with('address', 'contact', 'depts')->first();

        $idToCart = Cart::where('profile_id', $profile->id)->pluck('count', 'item_1c_id');

        // собираем 1c_id категорий расходных материалов
        $supplies_id = [];
        $cat_supplies = Category::where('parent_1c_id', 12710)->get(['1c_id'])->pluck('1c_id')->toArray();
        $supplies_id = array_merge($supplies_id, $cat_supplies);
        foreach($cat_supplies as $sub) {
            $sub_cat_supplies = Category::where('parent_1c_id', $sub)->get(['1c_id'])->pluck('1c_id')->toArray();
            if(count($sub_cat_supplies)) {
                $supplies_id = array_merge($supplies_id, $sub_cat_supplies);
            }
        }

        return view('service.includes.popup_spare', [
            'items' => $items,
            'idToCart' => $idToCart,
            'currency' => $request->get('currency', 'byn'),
            'items_yes' => $items_yes,
            'items_no' => $items_no,
            'scheme' => $scheme,
            'item_spares' => true, // метка для item_block_line
            'supplies_id' => $supplies_id
        ]);
    }


}

