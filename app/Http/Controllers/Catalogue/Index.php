<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Traits\CheapItemsTrait;
use App\Traits\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class Index extends Controller
{

    use MenuCategory, CheapItemsTrait;

    public function __invoke(Request $request, $id = null)
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

        $subCategories = $subCategory?->withSubCategories()->pluck('id_1c')->push(3149) ?? [];

        $items = Item::whereIn('category_id_1c', $subCategories)
            ->where([['category_id_1c', '!=', 0], ['id_1c', '!=', 0], ['is_component', '!=', 2]])
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
//        $items = Item::where([['category_id_1c', '!=', '0'], ['id_1c', '!=', '0'], ['is_component', '!=', 2]])
//            ->orderBy('name')
//            ->get();
//        dd($items);
        // собираем уцененные товары и добавляем к товарам
        $items = $this->getCheapItems($items);

        // Собираем коллекцию Категория-Товары*****************************************
        $collect = new Collection;

        // берем товары категории
        $cat_items = $items->where('category_id_1c', $subCategory->id_1c);

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
            $cat_items = $items->where('category_id_1c', $sub_cat->id_1c);
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
                    $cat_items = $items->where('category_id_1c', $sub_sub_cat->id_1c);

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
                            $cat_items = $items->where('category_id_1c', $sub_sub_sub_cat->id_1c);

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
        // $data['discounted_items'] = Item::where([['category_id_1c', 3149], ['count', '>', 0]])->get(['1c_id', 'name', 'discounted_rub']);

        // формирование линии ссылок (Хлебные крошки)
        $breadcrumbs = (new Category)->getBreadcrumbs($subCategory);

        $data['breadcrumbs'] = $breadcrumbs;
//        dd($data);
        return view(profile()->isDealer() ? 'catalogue.dealer' : 'catalogue.service')->with($data);
    }

}
