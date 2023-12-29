<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use App\Traits\ActionItemsTrait;
use App\Traits\CheapItemsTrait;

use App\Models\Category;
use App\Models\Item;
use App\Models\Discount;
use App\Models\Cart;

class NewCatalogController extends Controller
{
    use ActionItemsTrait, CheapItemsTrait;

    public function index(Request $request, $id = null)
    {
        // id профайла
        $profile_id = \Auth::user()->profile->id;

        // проверяем присутсвие категории в бд
        $cat_exist = Category::where('id_1c', $id)->count();

        // берем текущее значение сессии категории
        $cat_sess = $request->session()->get('current_cat');
//        $cat_sess = null;
        // назначаем id, если не существуют
        if (!$id) {
            if (!$cat_sess) {
//                $id = 7220;
                $id = 7024;
                $request->session()->put(['current_cat' => $id]);
            } else {
                $id = $request->session()->get('current_cat');
            }
        } else {
            // переназначаем id, если нет в базе
            if (!$cat_exist) {
                if (!$cat_sess) {
//                    $id = 7220;
                    $id = 7024;
                } else {
                    $id = $request->session()->get('current_cat');
                }
            }
            // переписываем сессию
            $request->session()->put('current_cat', $id);
        }

        // собираем категории для левого меню
        $menu_categories = $this->getMenuCategory();
        $data['categories'] = $menu_categories;

        $data['category_id'] = $id;

        // для подсчета количества новых товаров
        $new_items_count = Item::where('is_new_item', 1)
            ->where(function ($query) {
                $query->where('amount', '>', 0)
                    ->orWhere('locked', '>', 0);
            })
            ->get(['id']);
        $data['new_items_count'] = $new_items_count->count();

        $subCategory = (new Category)->where('id_1c', $id)->first();

        $subCategories = $subCategory?->withSubCategories()->pluck('id_1c')->push(3149);
//        dd($subCategories, $subCategory, $id);

        $items = Item::whereIn('1c_category_id', $subCategories)
            ->where([['1c_category_id', '!=', 0], ['id_1c', '!=', 0], ['is_component', '!=', 2]])
            ->with('images', 'category', 'discounts', 'guides')
//            ->withCount('getSchemeParent')
            ->orderBy('name')
            ->get();








        // берем требуемую категорию
//        $subCategory = (new Category)->where('id_1c', $id)->first();
//        if (!$subCategory) {
//            $subCategory = new Category;
//        }
//
//        // берем все товары
//        $items = Item::where([['1c_category_id', '!=', '0'], ['id_1c', '!=', '0'], ['is_component', '!=', 2]])
//            ->orderBy('name')
//            ->get();
//        dd($items);
        // собираем уцененные товары и добавляем к товарам
        $items = $this->getCheapItems($items);

        // Собираем коллекцию Категория-Товары*****************************************
        $collect = new Collection;

        // берем товары категории
        $cat_items = $items->where('1c_category_id', $subCategory->id_1c);

        if ($cat_items->count()) {
            // берем товары без архивных
            $cat_items_no_archive = $cat_items->where('in_archive', 0);

            // если не пусто, добавляем в коллекцию
            if ($cat_items_no_archive->count()) {
                $collect->push([
                    'name' => $subCategory->name,
                    'items' => $cat_items_no_archive->where('amount', '>', 0)->merge($cat_items_no_archive->where('amount', '<=', 0)),
                    'is_archive_cat' => false,
                ]);
            }
            // берем архивные товары
            $cat_items_archive = $cat_items->where('in_archive', 1);

            // если не пусто, добавляем в коллекцию
            if ($cat_items_archive->count()) {
                $collect->push([
                    'name' => $subCategory->name . " (архивные)",
                    'items' => $cat_items_archive->where('amount', '>', 0)->merge($cat_items_archive->where('amount', '<=', 0)),
                    'is_archive_cat' => true,
                ]);
            }
        }

        foreach ($subCategory->subCategory->sortBy('default_sort') as $sub_cat) {

            // берем товары категории
            $cat_items = $items->where('1c_category_id', $sub_cat->id_1c);
//            dd($cat_items, $sub_cat->id_1c);
            if ($cat_items->count()) {

                // берем товары без архивных
                $cat_items_no_archive = $cat_items->where('in_archive', 0);

                // если не пусто, добавляем в коллекцию
                if ($cat_items_no_archive->count()) {
                    $collect->push([
                        'name' => $sub_cat->name,
                        'items' => $cat_items_no_archive->where('amount', '>', 0)->merge($cat_items_no_archive->where('amount', '<=', 0)),
                        'is_archive_cat' => false,
                    ]);
                }

                // берем архивные товары
                $cat_items_archive = $cat_items->where('in_archive', 1);

                // если не пусто, добавляем в коллекцию
                if ($cat_items_archive->count()) {
                    $collect->push([
                        'name' => $sub_cat->name . " (архивные)",
                        'items' => $cat_items_archive->where('amount', '>', 0)->merge($cat_items_archive->where('amount', '<=', 0)),
                        'is_archive_cat' => true,
                    ]);
                }

            }

            if ($sub_cat->subCategory) {

                foreach ($sub_cat->subCategory->sortBy('default_sort') as $sub_sub_cat) {

                    // берем товары категории
                    $cat_items = $items->where('1c_category_id', $sub_sub_cat->id_1c);

                    if ($cat_items->count()) {

                        // берем товары без архивных
                        $cat_items_no_archive = $cat_items->where('in_archive', 0);

                        // если не пусто, добавляем в коллекцию
                        if ($cat_items_no_archive->count()) {
                            $collect->push([
                                'name' => $sub_sub_cat->name,
                                'items' => $cat_items_no_archive->where('amount', '>', 0)->merge($cat_items_no_archive->where('amount', '<=', 0)),
                                'is_archive_cat' => false,
                            ]);
                        }

                        // берем архивные товары
                        $cat_items_archive = $cat_items->where('in_archive', 1);

                        // если не пусто, добавляем в коллекцию
                        if ($cat_items_archive->count()) {
                            $collect->push([
                                'name' => $sub_sub_cat->name . " (архивные)",
                                'items' => $cat_items_archive->where('amount', '>', 0)->merge($cat_items_archive->where('amount', '<=', 0)),
                                'is_archive_cat' => true,
                            ]);
                        }

                    }

                    if ($sub_sub_cat->subCategory) {

                        foreach ($sub_sub_cat->subCategory->sortBy('default_sort') as $sub_sub_sub_cat) {

                            // берем товары категории
                            $cat_items = $items->where('1c_category_id', $sub_sub_sub_cat->id_1c);

                            if ($cat_items->count()) {

                                // берем товары без архивных
                                $cat_items_no_archive = $cat_items->where('in_archive', 0);

                                // если не пусто, добавляем в коллекцию
                                if ($cat_items_no_archive->count()) {
                                    $collect->push([
                                        'name' => $sub_cat->name,
                                        'items' => $cat_items_no_archive->where('amount', '>', 0)->merge($cat_items_no_archive->where('amount', '<=', 0)),
                                        'is_archive_cat' => false,
                                    ]);
                                }

                                // берем архивные товары
                                $cat_items_archive = $cat_items->where('in_archive', 1);

                                // если не пусто, добавляем в коллекцию
                                if ($cat_items_archive->count()) {
                                    $collect->push([
                                        'name' => $sub_cat->name . " (архивные)",
                                        'items' => $cat_items_archive->where('amount', '>', 0)->merge($cat_items_archive->where('amount', '<=', 0)),
                                        'is_archive_cat' => true,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
        $data['sub_categories'] = $collect;

        // *****************************************************************************

        // собираем все уцененные товары
        // $data['discounted_items'] = Item::where([['1c_category_id', 3149], ['count', '>', 0]])->get(['1c_id', 'name', 'discounted_rub']);

        // формирование линии ссылок (Хлебные крошки)
        $breadcrumbs = (new Category)->getBreadcrumbs($subCategory);

        $data['breadcrumbs'] = $breadcrumbs;
//        dd($data);
        return view(profile()->isDealer() ? 'catalogue.dealer' : 'catalogue.service')->with($data);
    }

    // Вывод новых товаров ***********************************************************
    public function newItems(Request $request)
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

        return view('catalog.new_new_items')->with($data);
    }

    // вывод всех Акций ***************************************************************
    public function allActions(Request $request)
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

    // Поиск ************************************************************************
    public function search(Request $request)
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
                    ['1c_category_id', '>', 0],
                    ['name', 'like', '%' . $searchKeywords[0] . '%'],
                ])
                    ->orWhere([
                        ['id_1c', '!=', 0],
                        ['is_component', '!=', 2],
                        ['1c_category_id', '>', 0],
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
                $qw = Item::where([['id_1c', '!=', 0], ['is_component', '!=', 2], ['1c_category_id', '>', 0]]);
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
            ->where('1c_category_id', '!=', '3149');
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
            ->where('1c_category_id', '3149');
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
            ->where('1c_category_id', '!=', '3149');
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
        // $cheap_items = Item::where([['1c_category_id', '3149'], ['count', '>', 0]])->get();
        // $data['cheap_items'] = $cheap_items;

        // передаем тип поиска
        $data['search_type'] = $request->type;

        return view('catalog.new_search_catalog')->with($data);
    }

    public function getMenuCategory()
    {
        $menu_categories = Category::with('subCategory')
            ->where([
                ['id_1c', '!=', 7212],
                // 7222 - категория сервисных работ
                ['parent_1c_id', '0']
            ]);

        // если сервис
        if (\Auth::user()->profile->role == 'Сервис') {
            // исключаем Запчасти и Уцененные
            $menu_categories = $menu_categories->whereNotIn('id_1c', [7166, 7131]);
        }

        $menu_categories = $menu_categories
            ->orderBy('default_sort')
            ->get(['name', 'id_1c', 'image_sm', 'default_sort']);

        return $menu_categories;
    }

}
