<?php

namespace App\Http\Controllers\Api_1c8;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Helpers\XMLHelper;
use Illuminate\Filesystem\Filesystem;

use App\Models\Category;
use App\Models\Item;
use App\Models\CharacteristicItem;
use App\Models\ItemImage;
use App\Models\ItemGuide;
use Illuminate\Support\Facades\Log;


class CategoryController extends BaseController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function postCreateOrUpdate(XMLHelper $XMLHelper, Request $request)
    {
        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // поля в XML, которые должны быть заполнены
        $requared_fields = ['id_1c', 'uuid', 'parent_id_1c', 'name',];

        // проверка на пустоту
        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        $data = $this->getCategoryData($this->xml_data);

        // берем имя изображения
        $img_input_name = trim($this->xml_data->image_path);
        $this->deleteOldImages($data['id_1c']);
        if (!empty($img_input_name)) {
            $isImageExists = $this->syncImages($img_input_name);
            if (!$isImageExists) {
                $response = new Response("Ошибка! Для категории {$data['id_1c']} изображение в хранилище отсутствует! Повторите отправку!", 200);
                return $response;
            }
        }

        $result = Category::updateOrCreate(['uuid' => $data['uuid']], $data);

        if ($result) {
            $response = new Response("Категория {$data['id_1c']} добавлена(обновлена) успешно!", 200);
        } else {
            $response = new Response("Ошибка! Категория {$data['id_1c']} НЕ добавлена! Повторите отправку!", 200);
        }

        return $response;
    }

    public function postDelete(XMLHelper $XMLHelper, Request $request)
    {
        $this->xml_data = $XMLHelper->get_xml($request);

        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        $requared_fields = ['uuid'];

        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        $uuid = intval(trim($this->xml_data->id_1c));

        $cat = Category::where('uuid', $uuid)->first(['id_1c']);
        if ($cat) {
            $cat->delete();
            // путь где лежат файлы категорий
            $cat_image_puth = config('ut.images_categories_path');

            // удаляем файлы
            @unlink($cat_image_puth . $cat->image);
            @unlink($cat_image_puth . $cat->image_sm);

            $response = new Response("Категория {$uuid} удалена!", 200);
        } else {
            $response = new Response("Ошибка! Категории {$uuid} НЕ существует!", 200);
        }

        return $response;

        // if (Category::where('1c_id', $uid)->count() == 1) {
        //     $category = Category::with('subCategory.subCategory')->where('1c_id', $uid)->first();
        //     foreach ($category->subCategory as $sc) {
        //         foreach ($sc->subCategory as $ssc) {
        //             $this->deleteItems($ssc->items);
        //             Characteristic::where('category_1c_id', $ssc->{'1c_id'})->delete();
        //         }
        //         $sc->subCategory()->delete();
        //         $this->deleteItems($sc->items);
        //         Characteristic::where('category_1c_id', $sc->{'1c_id'})->delete();
        //     }
        //     $category->subCategory()->delete();
        //     $this->deleteItems($category->items);
        //     $category->delete();
        //     Characteristic::where('category_1c_id', $uid)->delete();

        // } else {
        //     $this->xml->addChild('error', "Record with category {$uid} not exists!");
        //     $this->has_error = true;
        // }

        // if (!$this->has_error) {
        //     $this->xml->addChild('success', "Record with category {$uid} with characteristics successfully deleted!");
        // }

        // $response = new Response($this->xml->asXML(), 200);

    }

    public function deleteItems(XMLHelper $XMLHelper, Request $request)
    {

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // поля в XML, которые должны быть заполнены
        $requared_fields = ['delete',];

        // проверка на пустоту
        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        if (trim($this->xml_data->delete) == "all") {
            CharacteristicItem::query()->delete();
            ItemImage::query()->delete();
            ItemGuide::query()->delete();
            Item::query()->delete();
            Category::query()->delete();

            $file = new Filesystem;
            $file->cleanDirectory(config('ut.images_categories_path'));
            $file->cleanDirectory(config('ut.images_items_path'));
            $file->cleanDirectory(config('ut.images_guides_path'));

            return new Response("Таблицы (категорий, номенклатуры, характеристик товаров, изображений, инструкций) - очищены, файлы удалены.", 200);
        }

        $uuid = intval(trim($this->xml_data->delete));

        // собираем коды категорий
        $cat_codes = array();

        // берем категорию
        $cat_1 = Category::where('id_1c', $uuid)->first(['id_1c']);

        // если пусто
        if (!$cat_1) {
            // отдаем ошибку
            $response = new Response("Ошибка! Категории {$uuid} не существует.", 200);
            return $response;
        }

        // добавляем код категории в массив
        array_push($cat_codes, $cat_1->id_1c);

        // берем категории 2 уровня
        $cats_2 = Category::where('parent_id_1c', $cat_1->id_1c)->get(['id_1c']);

        // если не пусто
        if ($cats_2->count()) {

            // добавляем коды категории в массив
            foreach ($cats_2 as $cat_2) {

                // добавляем коды категории в массив
                array_push($cat_codes, $cat_2->id_1c);

                // берем категории 3 уровня
                $cats_3 = Category::where('parent_id_1c', $cat_2->id_1c)->get(['id_1c']);

                // если не пусто
                if ($cats_3->count()) {

                    // добавляем коды категории в массив
                    foreach ($cats_3 as $cat_3) {

                        // добавляем коды категории в массив
                        array_push($cat_codes, $cat_3->id_1c);

                        // берем категории 4 уровня
                        $cats_4 = Category::where('parent_id_1c', $cat_3->id_1c)->get(['id_1c']);

                        // если не пусто
                        if ($cats_4->count()) {
                            // добавляем коды категории в массив
                            foreach ($cats_4 as $cat_4) {
                                // добавляем коды категории в массив
                                array_push($cat_codes, $cat_4->id_1c);
                            }
                        }
                    }
                }
            }
        }


        // собираем товары
        $items = Item::whereIn('category_id_1c', $cat_codes)->get();

        // если не пусто
        if ($items->count()) {

            foreach ($items as $item) {

                // берем инструкции
                $guides = ItemGuide::where('item_uuid', $item->uuid)->get();

                // если не пусто
                if ($guides->count()) {
                    foreach ($guides as $guide) {
                        @unlink(config('ut.images_guides_path') . $guide->file);
                    }

                    ItemGuide::where('item_uuid', $item->uuid)->delete();

                }

                // удаляем характеристики
                CharacteristicItem::where('item_1c_id', $item->id_1c)->delete();

                // берем изображения
                $images = ItemImage::where('item_uuid', $item->uuid)->get();

                if ($images->count()) {
                    foreach ($images as $image) {
                        @unlink(config('ut.images_items_path') . $image->image);
                        @unlink(config('ut.images_items_path') . $image->image_mid);
                        @unlink(config('ut.images_items_path') . $image->image_sm);
                    }

                    ItemImage::where('item_uuid', $item->uuid)->delete();
                }

                @unlink(config('ut.images_items_path') . $item->image);
                @unlink(config('ut.images_items_path') . $item->image_mid);
                @unlink(config('ut.images_items_path') . $item->image_sm);

                // удаляем товар
                Item::where('id_1c', $item->id_1c)->delete();
            }
        }

        // собираем категории
        $cats = Category::whereIn('id_1c', $cat_codes)->get();

        foreach ($cats as $cat) {
            // удаляем файлы
            @unlink(config('ut.images_categories_path') . $cat->image);
            @unlink(config('ut.images_categories_path') . $cat->image_sm);
        }

        // удаляем категории
        Category::whereIn('id_1c', $cat_codes)->delete();

        return new Response("Категория {$uuid}, дочерние категории, товары - удалены", 200);

    }

    public function syncCategories(XMLHelper $XMLHelper, Request $request)
    {
        $answer = 'syncCategories. Xml received at '.nowTime();

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        if ($this->xml_data->category->count() <= 0) {
            return new Response("Нет категорий для загрузки!", 400);
        }

        $answer .= '. Loop started at '.nowTime();

        $categories = [];
        foreach ($this->xml_data->category as $category) {
            $data = $this->getCategoryData($category);
            if (!empty($data['image_path'])) {
                $this->syncImages($data['image_path']);
            }
            $categories[] = $data;

//            Category::create($data);
        }
        Category::insert($categories);

        $answer .= '. Loop ended at '. nowTime();

        return new Response("Категории загружены. ".$answer, 200);
    }

    private function getCategoryData(object $category): array
    {
        $category = $this->prepareData($category);
        if(!Category::find($category['parent_uuid'] ?? null)){
            $category['parent_uuid'] = null;
        }
        return $category;



//        if (trim($category->parent_id_1c) != '0') {
//            $parent_1c_id = intval(trim($category->parent_id_1c));
//            // $parent_1c_id = intval(explode('-', trim($category->parent_id_1c))[1]);
//        } else {
//            $parent_1c_id = 0;
//        }
//        $data = array(
//            'id_1c' => intval(trim($category->id_1c)),
//            'uuid' => trim($category->uuid),
//            'parent_uuid' => trim($category->parent_uuid),
//            'parent_id_1c' => $parent_1c_id,
//            'name' => trim($category->name),
//            'default_sort' => trim($category->default_sort),
//            'image' => '',
//            'image_sm' => '',
//        );
//        return $data;
    }

    private function getImageName(string $img_input_name, bool $isBig = true): string
    {
        $size = '_' . $isBig ?: 'sm_';
        return explode('.', $img_input_name)[0] . $size . time() . '.jpg';
    }

    private function deleteOldImages(int $id_c): void
    {
        $old_images = Category::where('id_1c', $id_c)->first(['image', 'image_sm']);
        if ($old_images) {
            @unlink(config('ut.images_categories_path') . $old_images->image);
            @unlink(config('ut.images_categories_path') . $old_images->image_sm);
        }
    }

    private function syncImages(string $img_input_name): bool
    {
        $isExists = file_exists(config('ut.images_input_path') . $img_input_name);
        if ($isExists) {
            $data['image'] = $this->getImageName($img_input_name);
            $data['image_sm'] = $this->getImageName($img_input_name, false);

            $this->imageHandler($img_input_name, $data['image'], config('ut.images_input_path'), config('ut.images_categories_path'), '250', '');
            $this->imageHandler($img_input_name, $data['image_sm'], config('ut.images_input_path'), config('ut.images_categories_path'), '', '40');

            @unlink(config('ut.images_input_path') . $img_input_name);
        }
        return $isExists;
    }

}
