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
        $requared_fields = [
            'id_1c',
            'parent_id_1c',
            'name',
        ];

        // проверка на пустоту
        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        // берем код категории
        $id_1c = trim($this->xml_data->id_1c);
        // $id_1c = intval(explode('-', trim($this->xml_data->id_1c))[1]);
        // берем код родителя
        if (trim($this->xml_data->parent_id_1c) != '0') {
            $parent_1c_id = trim($this->xml_data->parent_id_1c);
            // $parent_1c_id = intval(explode('-', trim($this->xml_data->parent_id_1c))[1]);
        } else {
            $parent_1c_id = 0;
        }

        // записываем данные из xml в data
        $data = array(
            'id_1c' => $id_1c,
            'parent_1c_id' => $parent_1c_id,
            'name' => trim($this->xml_data->name),
            'default_sort' => trim($this->xml_data->default_sort),
        );

        // берем имя изображения
        $img_input_name = trim($this->xml_data->image_path);
        // путь где лежит изображение
        $input_puth = '/home/alfastok/public_html/storage/ut_1c8/common/';
        // путь куда положим полученный файл
        $output_puth = '/home/alfastok/public_html/storage/ut_1c8/category-images/';

        // если есть изображение, обрабатываем
        if (!empty($img_input_name)) {

            // имя полученного большого файла
            $img_output_name = explode('.', $img_input_name)[0] . '_' . time() . '.jpg';
            // имя полученного малого файла
            $img_output_name_sm = explode('.', $img_input_name)[0] . '_sm_' . time() . '.jpg';

            // проверяем, есть ли изображение в хранилище
            // если нет
            if (!file_exists($input_puth . $img_input_name)) {

                // вернем ошибку
                $response = new Response("Ошибка! Для категории {$id_1c} изображение в хранилище отсутствует! Повторите отправку!", 200);
                return $response;
            } else {

                // ресайзим и переносим большое изображение
                $this->imageHandler($img_input_name, $img_output_name, $input_puth, $output_puth, '250', '');
                // ресайзим и переносим малое изображение
                $this->imageHandler($img_input_name, $img_output_name_sm, $input_puth, $output_puth, '', '40');

                // берем имя старых изображений
                $old_images = Category::where('id_1c', $id_1c)->first(['image', 'image_sm']);

                // если не пусто
                if ($old_images) {
                    // удаляем старые изображения
                    @unlink($output_puth . $old_images->image);
                    @unlink($output_puth . $old_images->image_sm);
                }

                // удаляем входящее изображение из хранилища
                @unlink($input_puth . $img_input_name);

                // добавляем в дату имена файлов
                $data['image'] = $img_output_name;
                $data['image_sm'] = $img_output_name_sm;
            }
        } else { // если в xml нет имени картинки

            // берем имя старых изображений
            $old_images = Category::where('id_1c', $id_1c)->first(['image', 'image_sm']);

            // если не пусто
            if ($old_images) {
                // удаляем старые изображения
                @unlink($output_puth . $old_images->image);
                @unlink($output_puth . $old_images->image_sm);
            }

            // добавляем в дату пустые имена файлов
            $data['image'] = "";
            $data['image_sm'] = "";
        }

        // добавляем или обновляем
        $result = Category::updateOrCreate(['id_1c' => $id_1c], $data);

        if ($result) {
            $response = new Response("Категория {$id_1c} добавлена(обновлена) успешно!", 200);
        } else {
            $response = new Response("Ошибка! Категория {$id_1c} НЕ добавлена! Повторите отправку!", 200);
        }

        return $response;
    }

    public function postDelete(XMLHelper $XMLHelper, Request $request)
    {

        // приходящая xml
        $xml = '
<?xml version="1.0" encoding="UTF-8"?>
<data  version="1.0">
<id_1c>00-00007173</id_1c>
</data>
        ';

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // поля в XML, которые должны быть заполнены
        $requared_fields = [
            'id_1c',
        ];

        // проверка на пустоту
        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        $id_1c = intval(trim($this->xml_data->id_1c));
        // $id_1c = intval(explode('-', trim($this->xml_data->id_1c))[1]);

        // проверяем, есть ли такая категория
        $cat = Category::where('id_1c', $id_1c)->first(['id_1c']);

        // если есть
        if ($cat) {

            // бетем изображения
            $image = $cat->image;
            $image_sm = $cat->image_sm;

            // удаляем категорию
            Category::where('id_1c', $id_1c)->delete();

            // удаляем картинки категории

            // путь где лежат файлы категорий
            $cat_image_puth = '/home/alfastok/public_html/storage/ut_1c8/category-images/';

            // удаляем файлы
            @unlink($cat_image_puth . $image);
            @unlink($cat_image_puth . $image_sm);

            $response = new Response("Категория {$id_1c} удалена!", 200);

        } else {
            // или отдаем сообщение
            $response = new Response("Ошибка! Категории {$id_1c} НЕ существует!", 200);
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
        $requared_fields = [
            'delete',
        ];

        // проверка на пустоту
        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        if (trim($this->xml_data->delete) == "all") {
            // очищаем таблицу категорий, номенклатуры, изображений, инструкций, характеристик товаров
            Category::truncate();
            Item::truncate();
            CharacteristicItem::truncate();
            ItemImage::truncate();
            ItemGuide::truncate();

            // путь где лежат изображения категорий
            $cat_image_puth = '/home/alfastok/public_html/storage/ut_1c8/category-images/';
            // путь где лежат изображения
            $image_puth = '/home/alfastok/public_html/storage/ut_1c8/item-images/';
            // путь где лежат инструкции
            $guide_puth = '/home/alfastok/public_html/storage/ut_1c8/item-guides/';

            // очищаем папки от файлов
            $file = new Filesystem;
            $file->cleanDirectory($cat_image_puth);
            $file->cleanDirectory($image_puth);
            $file->cleanDirectory($guide_puth);

            $response = new Response("Таблицы (категорий, номенклатуры, характеристик товаров, изображений, инструкций) - очищены, файлы удалены.", 200);
        } else {

            // берем код категории
            $id_1c = intval(trim($this->xml_data->delete));
            // $id_1c = intval(explode('-', trim($this->xml_data->delete))[1]);

            // собираем коды категорий
            $cat_codes = array();

            // берем категорию
            $cat_1 = Category::where('id_1c', $id_1c)->first(['id_1c']);

            // если пусто
            if (!$cat_1) {
                // отдаем ошибку
                $response = new Response("Ошибка! Категории {$id_1c} не существует.", 200);
                return $response;
            } else {

                // добавляем код категории в массив
                array_push($cat_codes, $cat_1->id_1c);

                // берем категории 2 уровня
                $cats_2 = Category::where('parent_1c_id', $cat_1->id_1c)->get(['id_1c']);

                // если не пусто
                if ($cats_2->count()) {

                    // добавляем коды категории в массив
                    foreach ($cats_2 as $cat_2) {

                        // добавляем коды категории в массив
                        array_push($cat_codes, $cat_2->id_1c);

                        // берем категории 3 уровня
                        $cats_3 = Category::where('parent_1c_id', $cat_2->id_1c)->get(['id_1c']);

                        // если не пусто
                        if ($cats_3->count()) {

                            // добавляем коды категории в массив
                            foreach ($cats_3 as $cat_3) {

                                // добавляем коды категории в массив
                                array_push($cat_codes, $cat_3->id_1c);

                                // берем категории 4 уровня
                                $cats_4 = Category::where('parent_1c_id', $cat_3->id_1c)->get(['id_1c']);

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
            }

            // собираем товары
            $items = Item::whereIn('1c_category_id', $cat_codes)->get();

            // если не пусто
            if ($items->count()) {

                foreach ($items as $item) {

                    // берем инструкции
                    $guides = ItemGuide::where('item_uuid', $item->uuid)->get();

                    // если не пусто
                    if ($guides->count()) {

                        // путь где лежат файлы
                        $file_puth = '/home/alfastok/public_html/storage/ut_1c8/item-guides/';

                        foreach ($guides as $guide) {
                            // удаляем файл
                            @unlink($file_puth . $guide->file);
                        }

                        // удаляем инструкции
                        ItemGuide::where('item_uuid', $item->uuid)->delete();

                    }

                    // удаляем характеристики
                    CharacteristicItem::where('item_1c_id', $item->id_1c)->delete();

                    // берем изображения
                    $images = ItemImage::where('item_uuid', $item->uuid)->get();

                    // путь где лежат файлы
                    $image_puth = '/home/alfastok/public_html/storage/ut_1c8/item-images/';

                    // если не пусто
                    if ($images->count()) {

                        // удаляем файлы
                        foreach ($images as $image) {
                            @unlink($image_puth . $image->image);
                            @unlink($image_puth . $image->image_mid);
                            @unlink($image_puth . $image->image_sm);
                        }

                        ItemImage::where('item_uuid', $item->uuid)->delete();
                    }

                    @unlink($image_puth . $item->image);
                    @unlink($image_puth . $item->image_mid);
                    @unlink($image_puth . $item->image_sm);

                    // удаляем товар
                    Item::where('id_1c', $item->id_1c)->delete();
                }
            }

            // собираем категории
            $cats = Category::whereIn('id_1c', $cat_codes)->get();

            // путь где лежат файлы категорий
            $cat_image_puth = '/home/alfastok/public_html/storage/ut_1c8/category-images/';

            foreach ($cats as $cat) {
                // удаляем файлы
                @unlink($cat_image_puth . $cat->image);
                @unlink($cat_image_puth . $cat->image_sm);
            }

            // удаляем категории
            Category::whereIn('id_1c', $cat_codes)->delete();

            $response = new Response("Категория {$id_1c}, дочерние категории, товары - удалены", 200);
        }

        return $response;
    }

    public function syncCategories(XMLHelper $XMLHelper, Request $request)
    {

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если в запросе есть категории
        if ($this->xml_data->category->count()) {

            // путь где лежат изображения
            $input_puth = '/home/alfastok/public_html/storage/ut_1c8/common/';
            // путь куда положим полученные файлы
            $output_puth = '/home/alfastok/public_html/storage/ut_1c8/category-images/';

            // обрабатываем
            foreach ($this->xml_data->category as $category) {

                // берем код категории
                $id_1c = intval(trim($category->id_1c));
                // $id_1c = intval(explode('-', trim($category->id_1c))[1]);
                // берем код родителя
                if (trim($category->parent_id_1c) != '0') {
                    $parent_1c_id = intval(trim($category->parent_id_1c));
                    // $parent_1c_id = intval(explode('-', trim($category->parent_id_1c))[1]);
                } else {
                    $parent_1c_id = 0;
                }

                // записываем данные из xml в data
                $data = array(
                    'id_1c' => $id_1c,
                    'parent_1c_id' => $parent_1c_id,
                    'name' => trim($category->name),
                    'default_sort' => trim($category->default_sort),
                );

                // берем имя изображения
                $img_input_name = trim($category->image_path);

                // если есть изображение, обрабатываем
                if (!empty($img_input_name)) {

                    // имя полученного большого файла
                    $img_output_name = explode('.', $img_input_name)[0] . '_' . time() . '.jpg';
                    // имя полученного малого файла
                    $img_output_name_sm = explode('.', $img_input_name)[0] . '_sm_' . time() . '.jpg';

                    // проверяем, есть ли изображение в хранилище
                    // если есть
                    if (file_exists($input_puth . $img_input_name)) {

                        // ресайзим и переносим большое изображение
                        $this->imageHandler($img_input_name, $img_output_name, $input_puth, $output_puth, '250', '');
                        // ресайзим и переносим малое изображение
                        $this->imageHandler($img_input_name, $img_output_name_sm, $input_puth, $output_puth, '', '40');

                        // удаляем входящее изображение из хранилища
                        @unlink($input_puth . $img_input_name);

                        // добавляем в дату имена файлов
                        $data['image'] = $img_output_name;
                        $data['image_sm'] = $img_output_name_sm;
                    } else {

                        // добавляем в дату пустые имена файлов
                        $data['image'] = "";
                        $data['image_sm'] = "";
                    }
                } else { // если в xml нет имени картинки

                    // добавляем в дату пустые имена файлов
                    $data['image'] = "";
                    $data['image_sm'] = "";
                }

                // добавляем
                Category::create($data);
            }

            $response = new Response("Категории загружены!", 200);

        } else {
            $response = new Response("Нет категорий для загрузки!", 200);
        }

        return $response;
    }



}
