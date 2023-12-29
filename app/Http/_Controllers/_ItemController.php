<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use App\Traits\CategoryItemsTrait;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Scheme;
use App\Models\SchemeItem;


class ItemController extends Controller
{
    use CategoryItemsTrait;

    public function index(Request $request, $itemId)
    {
        // берем данные товара
        $item = Item::where('id_1c', $itemId)->with('brand', 'category')->first();
        $data['item_card'] = $item;

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
        $advantages = trim($item->content);
        $advantages = explode("\n", $advantages);
        $data['advantages'] = $advantages;

        // собираем "Краткое описание" **************************************************
        $more_about = trim($item->more_about);
        $more_about = explode("\n", $more_about);
        $data['more_about'] = $more_about;

        // берем категорию товара *******************************************************
        $subCategory = $item->category;
//        $subCategory = (new Category)->where('id_1c', $item->{'1c_category_id'})->first();

        if ($subCategory) {
            $breadcrumbs = (new Category)->getBreadcrumbs($subCategory);
            $data['breadcrumbs'] = $breadcrumbs;
        }

        // если с переходом на запчасти **********************************************
        $data['spares_link'] = (int)$request->spares;

        // собираем характеристики в коллекцию *****************************************
        $characteristics = new Collection;

        // берем значения характеристик
        $chars = $item->charValues;
        dd($chars);
        // если есть
        if ($chars->count()) {
            foreach ($chars as $char) {

                // берем имя характеристики
                $char_name = $char->charName->name;

                // добавляем в коллекцию
                $characteristics->push([
                    'name' => $char_name,
                    'value' => $char->value,
                ]);
            }
        }

        $data['characteristics'] = $characteristics;

        // ******************************************************************************

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

        return view('catalog.item_card')->with($data);

    }
}
