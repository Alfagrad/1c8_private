<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Scheme;
use App\Models\SchemeItem;
use App\Traits\CategoryItemsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class Index extends Controller
{

    use CategoryItemsTrait;

    public function __invoke(Request $request, $itemId)
    {
        // берем данные товара
        $item = Item::where('id_1c', $itemId)->with(['brand', 'category', 'charValues'])->first();
        $data['item_card'] = $item;

        $data['discount_values'] = $item->discount_values;

        // собираем изображения ****************************************
        $images = new Collection;
        // добавляем в коллекцию главные изображения
        if($item->image) {
            $images->push([
                'image_sm' => $item->image_sm,
                'image' => $item->image,
            ]);
        }
        // собираем дополнительные изображения
        $linked_img = ItemImage::where('item_uuid', $item->uuid)->get();
        if ($linked_img->count()) {
            foreach ($linked_img as $img) {
                $images->push([
                    'image_sm' => $img->image_sm,
                    'image' => $img->image,
                ]);
            }
        }
        $data['images'] = $images;

        // собираем "Преимущества" *****************************************************
        $data['advantages'] = explode("\n", trim($item->content));;

        // собираем "Краткое описание" **************************************************
        $data['more_about'] = explode("\n", trim($item->more_about));

        // берем категорию товара *******************************************************
        $subCategory = $item->category;
//        $subCategory = (new Category)->where('id_1c', $item->{'1c_category_id'})->first();

        if ($subCategory) {
            $breadcrumbs = (new Category)->getBreadcrumbs($subCategory);
            $data['breadcrumbs'] = $breadcrumbs;
        }

        // если с переходом на запчасти **********************************************
        $data['spares_link'] = (int)$request->spares;

        // собираем массив uuid схем запчастей если есть
        if ($item->schemes) {
            $scheme_arr = array_map('trim', array_unique(array_filter(explode(',', $item->schemes))));

            // собираем схемы
            $schemes = Scheme::whereIn('uuid', $scheme_arr)->get();

            // собираем запчасти
            $spares = SchemeItem::whereIn('scheme_uuid', $scheme_arr)->orderBy('position')->with('item')->get();

            // добавим id схемы в коллекцию
            foreach ($spares as $spare) {
                // определяем id схемы
                $scheme_id = Scheme::where('uuid', $spare->scheme_uuid)->first(['id'])->id;
                // дописываем в коллекцию
                $spare->scheme_id = $scheme_id;
            }

        } else {
            $schemes = new Collection;
            $spares = new Collection;
        }

        $data['schemes'] = $schemes;
        $data['spares'] = $spares;

        // определяем тип юзера
        $client_type = \Auth::user()->profile->role;

        // собираем - С этим товаром покупают *********************

        // собираем данные
        $buy_with_cat = new Collection;
        $buy_with = new Collection;
        $buy_with_count = 0;

        // если не Cервис
        if ($client_type != 'Сервис') {

            // если есть коды в бд
            if ($item->buy_with) {

                // берем массив кодов
                $f_codes_arr = explode(',', trim($item->buy_with));

                // если не пусто
                if (count($f_codes_arr)) {

                    // убираем нули
                    foreach ($f_codes_arr as $key => $val) {
                        $f_codes_arr[$key] = intval(explode('-', $val)[1]);
                    }

                    // собираем коллекцию Категория - Товары
                    $collect = $this->getCategoryItems($f_codes_arr);
                    $buy_with_cat = $collect;

                    // если 1с код товар
                    $buy_with = Item::whereIn('id_1c', $f_codes_arr)
                        ->where('in_archive', 0)
                        ->where(function($query){
                            $query->where('amount', '>', 0)->orWhere('locked', '>', 0);
                        })
                        ->orderBy('name')
                        ->get();

                    $buy_with = $buy_with;
                    $uy_with_count = $buy_with->count();
                }
            }
        }

        $data['buy_with_cat'] = $buy_with_cat;
        $data['buy_with'] = $buy_with;
        $data['buy_with_count'] = $buy_with_count;

        // собираем услуги для Сервиса

        // собираем данные
        $services = new Collection;

        // если Cервис
        if ($client_type == 'Сервис') {

            // берем категорию для вывода
            $service_cat = 7214;

            // если есть
            if ($service_cat) {

                // берем категорию
                $category = Category::where('id_1c', $service_cat)->first(['id_1c', 'name']);

                // если есть
                if($category) {

                    // берем товары категории
                    $cat_items = Item::where('1c_category_id', $category->id_1c)
                        ->orderBy('name')
                        ->get();

                    // если есть
                    if($cat_items->count()) {

                        // добавляем в коллекцию
                        $services->push([
                            'name' => $category->name,
                            'items' => $cat_items,
                        ]);
                    }
                }
            }
        }
        $data['services'] = $services;

        return view('item.index')->with($data);

    }

}
