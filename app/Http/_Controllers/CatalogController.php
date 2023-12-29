<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Item;
use App\Models\Scheme;
use App\Models\SchemeParts;
use App\Models\User;
use App\Models\BrandCertificate;
use App\Models\BrandGuide;
use Illuminate\Http\Request;
use Auth;
use Intervention\Image\Facades\Image as Image;


class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // если требуется вывод только новинок, удаляем сессию с id категории
        if($request->new_items AND $request->new_items == 'yes') {
            session()->forget('current_category');
        }
        // dd(session('current_category'));
        if (session('current_category')) {
            return   redirect('/catalog/'.session('current_category'));
        }

        $data = $this->getUserData($request);

        $categoriesList = Category::with(['subCategory' => function ($query) {
            $query->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC');
        }])->where('parent_1c_id', 0)->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC')->get();

        // обработка изображений категорий*******************************
        // путь к файловуму хранилищу изображений
        $storage_path = public_path().'/storage/item-images/';
        // путь к хранилищу изображений категорий
        $cat_path = public_path().'/storage/categories/';

        foreach($categoriesList as $main_cat) {

            foreach($main_cat->subCategory as $sub_cat){
                // не прописан путь к изображению, пропускаем
                if(!trim($sub_cat->image_path)) continue;
                // выделяем имя файла изображения
                $img_name = explode('/', trim($sub_cat->image_path))[1];
                // если файла в хранилище нет, пропускаем
                if(!file_exists($storage_path.$img_name)) {
                    continue;
                } else {
                    // если файл существует в папке categories
                    if(file_exists($cat_path.$img_name)) {
                        // берем дату последнего изменения файла в папке хранилища
                        $file_upd = filemtime($storage_path.$img_name);
                        // если не соответствует бд, ресайзим и переносим в хранилище изображений категорий
                        if($file_upd != $sub_cat->image_date) {
                            $this->imageHandler($img_name, $storage_path, $cat_path, '', '40');
                            // записываем в бд дату последнего изменения файла
                            $cat_upd = Category::where('id', $sub_cat->id)->first(['id', 'image_date']);
                            $cat_upd->image_date = filemtime($storage_path.$img_name);
                            $cat_upd->update();
                        }
                    }
                    // если не существует
                    else {
                        // ресайзим и переносим в хранилище изображений категорий
                        $this->imageHandler($img_name, $storage_path, $cat_path, '', '40');
                        // записываем в бд дату последнего изменения файла
                        $cat_upd = Category::where('id', $sub_cat->id)->first(['id', 'image_date']);
                        $cat_upd->image_date = filemtime($storage_path.$img_name);
                        $cat_upd->update();
                    }
                }
            }
        }
        //************************************************************

        $data['useLocalStorage'] = true;
        $data['searchCategory'] = false;

        // для подсчета количества новых товаров
        $new_items_count = Item::where('is_new_item', 1)->where('count', '>', 0)->get(['id']);
        $data['new_items_count'] = $new_items_count->count();

        return view('catalog.catalog', ['categories' => $categoriesList])->with($data);
    }


    public function currentCategory(Request $request, $id)
    {
        session(['current_category' => $id]);
        $data = $this->getUserData($request);

        $categoriesList = Category::with(['subCategory' => function ($query) {
            $query->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC');
        }])->where('parent_1c_id', 0)->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC')->get();

        // обработка изображений категорий*******************************
        // путь к файловуму хранилищу изображений
        $storage_path = public_path().'/storage/item-images/';
        // путь к хранилищу изображений категорий
        $cat_path = public_path().'/storage/categories/';

        foreach($categoriesList as $main_cat) {

            foreach($main_cat->subCategory as $sub_cat){
                // не прописан путь к изображению, пропускаем
                if(!trim($sub_cat->image_path)) continue;
                // выделяем имя файла изображения
                $img_name = explode('/', trim($sub_cat->image_path))[1];
                // если файла в хранилище нет, пропускаем
                if(!file_exists($storage_path.$img_name)) {
                    continue;
                } else {
                    // если файл существует в папке categories
                    if(file_exists($cat_path.$img_name)) {
                        // берем дату последнего изменения файла в папке хранилища
                        $file_upd = filemtime($storage_path.$img_name);
                        // если не соответствует бд, ресайзим и переносим в хранилище изображений категорий
                        if($file_upd != $sub_cat->image_date) {
                            $this->imageHandler($img_name, $storage_path, $cat_path, '', '40');
                            // записываем в бд дату последнего изменения файла
                            $cat_upd = Category::where('id', $sub_cat->id)->first(['id', 'image_date']);
                            $cat_upd->image_date = filemtime($storage_path.$img_name);
                            $cat_upd->update();
                        }
                    }
                    // если не существует
                    else {
                        // ресайзим и переносим в хранилище изображений категорий
                        $this->imageHandler($img_name, $storage_path, $cat_path, '', '40');
                        // записываем в бд дату последнего изменения файла
                        $cat_upd = Category::where('id', $sub_cat->id)->first(['id', 'image_date']);
                        $cat_upd->image_date = filemtime($storage_path.$img_name);
                        $cat_upd->update();
                    }
                }
            }
        }
        //************************************************************


        $request->categoryId = $id;
        $blockSearchCatalog =   $this->refresh($request);

        $data['useLocalStorage'] = false;
        $data['cancelRefresh'] = true;
        $data['searchCategory'] = $id;

        // для подсчета количества новых товаров
        $new_items_count = Item::where('is_new_item', 1)->where('count', '>', 0)->get(['id']);
        $data['new_items_count'] = $new_items_count->count();
        return view('catalog.catalog', [
            'categories' => $categoriesList,
            'blockSearchCatalog' => $blockSearchCatalog,
        ])->with($data);
    }

    // обработка изображений ***************************************************
    public function imageHandler($img_name, $input_path, $output_path, $width, $height) {
        // $img_name - имя файла
        // $input_path - путь где брать файл
        // $output_path - путь куда положить
        // $width - ширина для исходящего изобр
        // $height - высота для исходящего изобр

        if(!$width) $with = null;
        if(!$height) $height = null;

        $img_result = Image::make($input_path.$img_name)
            ->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($output_path.$img_name);
    }

    public function search(Request $request)
    {

        $data = $this->getUserData($request);
        $profile = $request->user()->profile()->with('address', 'contact', 'depts')->first();
        $searchKeyword = $request->search_keywords;

        $items = Item::with('images')->where([['is_component', '!=', 2], ['in_archive', 0]]); // исключаем услуги и архивные

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

        $archive = Item::with('images')->where([['is_component', 0], ['in_archive', 1]]);

        if($request->type === 'products') {
            $items = $items->where([['1c_category_id', '>',0]]);
        }
        if($request->type === 'spares') {
            $items = $items->where([['is_component', '=', 1]]);
        }

        $searchKeywords = explode(' ', $searchKeyword);

        foreach ($searchKeywords as $keyword) {
            $items->where('name', 'like', '%' . $keyword . '%');
            $archive->where('name', 'like', '%' . $keyword . '%');
        }

        $products_yes = clone $items;

        // если вводится число, поиск по коду товара
//        $item_by_code = clone $items;
        if(count($searchKeywords) === 1 AND is_numeric($searchKeywords[0])) {
            $item_by_code = Item::with('images')->where('1c_id',  $searchKeywords[0])->first();
            if((bool)$item_by_code) $products_yes = true;
                // else $products_yes = false;
        } else {
            $item_by_code = '';
            // $products_yes = (bool)$products_yes->where('1c_category_id', '>',0)->get()->count();
        }


        $spares_yes = clone $items;
        $spares_yes = (bool)$spares_yes->where('is_component', 1)->get()->count();

        // товары в наличии
        $items_yes = clone $items;
        $items_yes = $items_yes->where([['count', '>', 0], ['in_archive', 0], ['1c_category_id', '!=', 3149]])->get();

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

        //$items_spares_yes = clone $items;
        //$items_products_no = clone $items;

        $categories = Category::with(['subCategory' => function ($query) {
            $query->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC');
        }], 'subCategory.subCategory', 'parentCategory')->where('parent_1c_id', 0)->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC')->get();


        $idToCart = Cart::where('profile_id', $profile->id)->pluck('count', 'item_1c_id');


        $blockSearchCatalog = '';
        $type = $request->type ? $request->type : 'products';

        // наценка/скидка markup. Учитывается в отображении цен на сайте
        $markup = $data['generalProfile']->markup;

        if(
            !(bool)$item_by_code
            && !(bool)$items_yes->count()
            && !(bool)$items_no->count()
            && !(bool)$archive->count()
            && !(bool)$items_yes_low_cost->count()
        ) {
            $blockSearchCatalog = view('catalog.search_answer', compact('searchKeyword', 'type'));
        } else {
            $blockSearchCatalog = view('catalog.search', [
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
                'data_markup' => $markup,
                'searchKeyword' => $searchKeyword,
            ]);
        }

        $data['useLocalStorage'] = false;
        $data['cancelRefresh'] = true;

        // для подсчета количества новых товаров
        $new_items_count = Item::where('is_new_item', 1)->where('count', '>', 0)->get(['id']);
        $data['new_items_count'] = $new_items_count->count();

        return view('catalog.catalog', [
            'categories' => $categories,
            'search' => true,
            'type' => $type,
            'blockSearchCatalog' => $blockSearchCatalog,
        ])->with($data);
    }

    public function newItems(Request $request)
    {
        $data = $this->getUserData($request);

        $profile = $request->user()->profile()->with('address', 'contact', 'depts')->first();

        $items = Item::with('images')->where('is_new_item', 1)->where('count', '>', 0)->orderBy('date_new_item', 'desc')->get();

        $categories = Category::with(['subCategory' => function ($query) {
            $query->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC');
        }], 'subCategory.subCategory', 'parentCategory')->where('parent_1c_id', 0)->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC')->get();

        $idToCart = Cart::where('profile_id', $profile->id)->pluck('count', 'item_1c_id');

        $chipItems = Item::where([['1c_category_id', '3149'], ['count', '>', 0]])->get();

        // наценка/скидка markup. Учитывается в отображении цен на сайте
        $markup = $data['generalProfile']->markup;

        $blockSearchCatalog = '';

        if(!(bool)$items->count()) {
            $blockSearchCatalog = "<p style='font-size: 20px; font-weight: bold; color: #870020;'>Пока ничего нового!</p>";
        } else {
            $blockSearchCatalog = view('catalog.new_items', [
                'items' => $items,
                'idToCart' => $idToCart,
                'currency' => $request->get('currency', 'byn'),
                'token' => Auth::user()->login_token,
                'chipItems' => $chipItems,
                'data_markup' => $markup,
            ]);
        }

        $data['useLocalStorage'] = false;
        $data['cancelRefresh'] = true;
        $data['new_items_count'] = $items->count();

        return view('catalog.catalog', [
            'categories' => $categories,
            'search' => true,
            'blockSearchCatalog' => $blockSearchCatalog,
        ])->with($data);
    }

    public function allActions(Request $request)
    {
        $data = $this->getUserData($request);

        $profile = $request->user()->profile()->with('address', 'contact', 'depts')->first();

        // собираем акционные товары с датой акции, сортируем по дате
        $items_date = Item::with('images')->where([['discounted', '!=', '0'], ['count', '>', '0'], ['date_open_action', '!=', '0000-00-00']])->whereNotIn('1c_category_id', [3149, 0])->orWhere([['is_action', '1'], ['count', '>', '0'], ['date_open_action', '!=', '0000-00-00']])->orderBy('date_open_action', 'desc')->get();
        // собираем акционные товары без даты акции, сортируем по имени
        $items_no_date = Item::with('images')->where([['discounted', '!=', '0'], ['count', '>', '0'], ['date_open_action', '0000-00-00']])->whereNotIn('1c_category_id', [3149, 0])->orWhere([['is_action', '1'], ['count', '>', '0'], ['date_open_action', '0000-00-00']])->orderBy('name')->get();
        // объединяем
        $items = $items_date->merge($items_no_date);

        $categories = Category::with(['subCategory' => function ($query) {
            $query->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC');
        }], 'subCategory.subCategory', 'parentCategory')->where('parent_1c_id', 0)->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC')->get();

        $idToCart = Cart::where('profile_id', $profile->id)->pluck('count', 'item_1c_id');

        $chipItems = Item::where([['1c_category_id', '3149'], ['count', '>', 0]])->get();

        // наценка/скидка markup. Учитывается в отображении цен на сайте
        $markup = $data['generalProfile']->markup;

        $blockSearchCatalog = '';

        if(!(bool)$items->count()) {
            $blockSearchCatalog = "<p style='font-size: 20px; font-weight: bold; color: #870020;'>Пока нет акций!</p>";
        } else {
            $blockSearchCatalog = view('catalog.all_action', [
                'items' => $items,
                'idToCart' => $idToCart,
                'currency' => $request->get('currency', 'byn'),
                'token' => Auth::user()->login_token,
                'chipItems' => $chipItems,
                'data_markup' => $markup,
            ]);
        }

        $data['useLocalStorage'] = false;
        $data['cancelRefresh'] = true;

        // для подсчета количества новых товаров
        $new_items_count = Item::where('is_new_item', 1)->where('count', '>', 0)->get(['id']);
        $data['new_items_count'] = $new_items_count->count();

        return view('catalog.catalog', [
            'categories' => $categories,
            'search' => true,
            'blockSearchCatalog' => $blockSearchCatalog,
        ])->with($data);
    }

    public function profile(Request $request)
    {
        $data = $this->getUserData($request);

        $data['useLocalStorage'] = false;
        $data['searchCategory'] = false;

        $profile = $request->user()->profile()->with('address', 'contact', 'depts')->first();
        $idToCart = Cart::where('profile_id', $profile->id)->pluck('count', 'item_1c_id');

        $categoriesRequest = Category::with(['items' => function ($query) {
            $query->orderBy('price_bel', 'asc');
        }, 'items.actions', 'items.gift', 'items.cheap_good', 'parentCategory']);

        $categoriesRequest->orWhereIn('parent_1c_id', [7661, 4585, 4667]);


        $categories = $categoriesRequest->orderBy('parent_1c_id')->get();
        /*
                foreach ($categories as $category){
                    if($category->parentCategory->parent_1c_id == 0){
                        $category->sort_id = strtolower($category->parentCategory->default_sort . ' ' .  $category->default_sort);
                    } else {
                        $category->sort_id = strtolower($category->parentCategory->parentCategory->default_sort . ' ' .  $category->default_sort);
                    }


                }
        */

        $blockSearchCatalog = '';

        $chipItems = $this->getChipItemsFromCategories($categories);
        $blockSearchCatalog = view('catalog.refresh_catalog_prof', [
            'categories' => $categories,
            'idToCart' => $idToCart,
            'currency' => $request->get('currency', 'byn'),
            'chipItems' => $chipItems,
            'token' => Auth::user()->login_token,
        ]);

        // для подсчета количества новых товаров
        $new_items_count = Item::where('is_new_item', 1)->where('count', '>', 0)->get(['id']);
        $data['new_items_count'] = $new_items_count->count();

        return view('catalog.catalog_prof', [
            'categories' => $categories,
            'blockSearchCatalog' => $blockSearchCatalog,
        ])->with($data);
    }


    // Так же нужен аякс который будет отдавать разные данные
    public function refresh(Request $request)
    {


        $data = $this->getUserData($request);
        $categoryId = $request->categoryId;

        $sort = $request->resfesh_category_opt['sort'];
        if ($sort == 'low-cost') {
            $categoriesRequest = Category::with(['items' => function ($query) {
                $query->orderBy('price_bel', 'asc');
            }, 'items.actions', 'items.images', 'items.gift', 'items.cheap_good', 'parentCategory', 'items.getChilds', 'items.getParent']);

        } elseif ($sort == 'high-cost') {
            $categoriesRequest = Category::with(['items' => function ($query) {
                $query->orderBy('price_bel', 'desc');
            }, 'items.actions', 'items.images', 'items.gift', 'items.cheap_good', 'parentCategory', 'items.getChilds', 'items.getParent']);
        } elseif ($sort == 'new') {
            $categoriesRequest = Category::with(['items' => function ($query) {
                $query->where('is_component', 0)
                    ->orderBy('code', 'desc');
            }, 'items.actions', 'items.images', 'items.gift', 'items.cheap_good', 'parentCategory', 'items.getChilds', 'items.getParent']);
        } elseif ($sort == 'old') {
            $categoriesRequest = Category::with(['items' => function ($query) {
                $query->orderBy('code', 'asc');
            }, 'items.actions', 'items.images', 'items.gift', 'items.cheap_good', 'parentCategory', 'items.getChilds', 'items.getParent']);
        } elseif ($sort == 'popular') {
            $categoriesRequest = Category::with(['items' => function ($query) {
                $query->where('is_component', 0)
                    ->orderBy('popular', 'asc');
            }, 'items.actions', 'items.images', 'items.gift', 'items.cheap_good', 'parentCategory', 'items.getChilds', 'items.getParent']);

        } else {
            $categoriesRequest = Category::with(['items' => function ($query) {
                $query->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC');
            }, 'items.actions', 'items.images', 'items.gift', 'items.cheap_good', 'parentCategory.parentCategory', 'items.getChilds', 'items.getParent'], ['subCategory' => function ($query) {

            }]);
        }


        //создадим сортировку и для запчастей


        if ($sort == 'low-cost') {
            $categoriesRequestDetails = Category::with(['items' => function ($query) {
                $query->orderBy('price_bel', 'asc');
            }, 'items.actions', 'items.images', 'items.gift', 'items.cheap_good', 'parentCategory', 'items.getChilds', 'items.getParent']);

        } elseif ($sort == 'high-cost') {
            $categoriesRequestDetails = Category::with(['items' => function ($query) {
                $query->orderBy('price_bel', 'desc');
            }, 'items.actions', 'items.images', 'items.gift', 'items.cheap_good', 'parentCategory', 'items.getChilds', 'items.getParent']);
        } elseif ($sort == 'new') {
            $categoriesRequestDetails = Category::with(['items' => function ($query) {
                $query->orderBy('code', 'desc');
            }, 'items.actions', 'items.images', 'items.gift', 'items.cheap_good', 'parentCategory', 'items.getChilds', 'items.getParent']);
        } elseif ($sort == 'old') {
            $categoriesRequestDetails = Category::with(['items' => function ($query) {
                $query->orderBy('code', 'asc');
            }, 'items.actions', 'items.images', 'items.gift', 'items.cheap_good', 'parentCategory', 'items.getChilds', 'items.getParent']);
        } elseif ($sort == 'popular') {
            $categoriesRequestDetails = Category::with(['items' => function ($query) {
                $query->orderBy('popular', 'asc');
            }, 'items.actions', 'items.images', 'items.gift', 'items.cheap_good', 'parentCategory', 'items.getChilds', 'items.getParent']);

        } else {
            $categoriesRequestDetails = Category::with(['items' => function ($query) {
                $query->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC');
            }, 'items.actions', 'items.images', 'items.gift', 'items.cheap_good', 'parentCategory.parentCategory', 'items.getChilds', 'items.getParent'], ['subCategory' => function ($query) {

            }]);
        }


        $profile = $request->user()->profile()->with('address', 'contact', 'depts')->first();
        //dd($request->categories);
//        $subCategory = Category::with(['subCategory' => function ($query) {
//            $query->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC');
//        }])->where('1c_id', $categoryId)->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC')->first();



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



        $breadcrumbs = (new Category)->getBreadcrumbs($subCategory);


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
//        dd($subcategories);
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

        // Из категорий получаем все cheaps
        $chipItems = $this->getChipItemsFromCategories($categories);
// dd($chipItems);

        // наценка/скидка markup. Учитывается в отображении цен на сайте
        $markup = $data['generalProfile']->markup;

        return view('catalog.refresh_catalog', [
            'categories' => $collection,
            'item_analogs' => $item_analogs,
            'categoriesDetails' => $categoriesDetails,
            'idToCart' => $idToCart,
            'breadcrumbs' => $breadcrumbs,
            'currency' => $request->get('currency', 'byn'),
            'chipItems' => $chipItems,
            'data_markup' => $markup,
            'token' => Auth::user()->login_token,
        ]);
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


        //$spares = array_unique($spares);


        /*$items = $items_yes = $items_no = Item::with(['actions', 'images', 'gift', 'cheap_good', 'getChilds', 'getParent'])
            ->whereIn('1c_id', $spares)
            ->get();
            //->first()
            //->getSchemeParent;
            //->sortBy('number_in_schema');*/


        $items_yes = false;//(bool)$items_yes->where('count', '>', '0')->count();
        $items_no = false;//(bool)$items_no->where('count', '=', '0')->count();

        $profile = $request->user()->profile()->with('address', 'contact', 'depts')->first();

        $idToCart = Cart::where('profile_id', $profile->id)->pluck('count', 'item_1c_id');

        // Из категорий получаем все cheaps
        //$chipItems = $this->getChipItemsFromCategories($categories);

        // наценка/скидка markup. Учитывается в отображении цен на сайте
        $markup = $data['generalProfile']->markup;

        // метка для сервисного центра
        $is_service = intval($profile->is_service);

        return view('catalog.popup_spare', [
            'items' => $items,
            //'items' => collect(),
            'idToCart' => $idToCart,
            'currency' => $request->get('currency', 'byn'),
            'items_yes' => $items_yes,
            'items_no' => $items_no,
            'scheme' => $scheme,
            'item_spares' => true, // метка для item_block_line
            'data_markup' => $markup,
            'is_service' => $is_service,
            //'chipItems' => $chipItems,
        ]);
    }


    public function item(Request $request, $itemId)
    {
        $data = $this->getUserData($request);
        $profile = $request->user()->profile()->first();
        $item = Item::where('1c_id', $itemId)->first();
        //$discountedItem = Item::where('category_id', $item->category_id)->where('discounted', 1)->get();
        $discountedItem = Item::whereIn('1c_id', explode(',', $item->cheap_goods))->get();
        $buyWith = Item::whereIn('1c_id', explode(',', $item->buy_with))->orderBy('name')->get();
        $buyWithCategory = Category::with('items')->whereIn('1c_id', explode(',', $item->buy_with))->get();
        $buyForget = Item::whereIn('1c_id', explode(',', $item->forget_buy))->get();
        $countItemInCart = Cart::where('profile_id', $profile->id)->where('item_1c_id', $item->{'1c_id'})->first();

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
        // удаляем если нет в наличии
        foreach ($items as $key => $val) {
            if($val->getItem->count < 1) {
                unset($items[$key]);
            }
        }

        $category = Category::where('1c_id', $item->{'1c_category_id'})->first();
        $breadcrumbs = null;
        if($category != null) {

            $breadcrumbs = (new Category)->getBreadcrumbs($category);
        }

        $spares_analogs = Item::where([['is_component', '1'], ['1c_category_id', '>', 0]])->get(['1c_id']);

        // собираем аналоги
        foreach($spares_analogs as $val) {
            $scheme_parts = SchemeParts::where('spare_id', $val->{'1c_id'})->get();
            $itm = collect();
            foreach ($scheme_parts as $part) {
                $tmp = SchemeParts::where([
                    ['scheme_id', $part->scheme_id],
                    ['number_in_schema', $part->number_in_schema],
                    ['spare_id', '!=', $val->{'1c_id'}],
                ])->get(['spare_id']);
                $itm->push($tmp);
            }
            $itm = $itm->flatten()->unique();
            $item_analogs[$val->{'1c_id'}] = $itm;
        }

        // наценка/скидка markup. Учитывается в отображении цен на сайте
        $markup = $data['generalProfile']->markup;

        return view('catalog.item', [
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
            'item_analogs' => $item_analogs,
            'chipItems' => $chipItems,
            'data_markup' => $markup,
        ])->with($data);

    }

    protected function getChipItemsFromCategories($categories)
    {
        $categoryIds = $categories->map(function ($cat) {
            return $cat->{'1c_id'};
        });

        $cheapsList = Item::whereIn('1c_category_id', $categoryIds)->where('cheap_goods', '<>', '')->pluck('cheap_goods');

        $cheapIds = [];
        foreach ($cheapsList as $chStr) {
            $chIds = explode(',', $chStr);
            foreach ($chIds as $chId) {
                $cheapIds[] = $chId;
            }
        }

        return Item::whereIn('1c_id', $cheapIds)->get();
    }


}

