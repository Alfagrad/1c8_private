<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

use App\Models\ItemImage;
use App\Models\Item;
use App\Models\Category;
use App\Models\ResizeTime;
use Intervention\Image\Facades\Image;


class ImageResizeController extends Controller
{
    public function first()
    {
        // фиксируем временную метку ресайза в БД
        $time = new ResizeTime;
        $time->date = date('Y-m-d H:i:s');


        // ресайз изображений категорий ****************************************

        // собираем категории
        $categories = Category::where('image_path', '!=', NULL)->get(['1c_id', 'image_path']);

        // ресайзим и переносим в папку catalog-thumbs
        // путь где лежит исходный файл
        $input_puth = public_path().'/storage/item-images/';
        // путь куда положим полученный файл
        $output_puth = public_path().'/storage/catalog-thumbs/';

        $arr_cat = "";
        foreach($categories as $image) {
            $image_name = explode('/', trim($image->image_path))[1];
            // имя исходного файла
            $img_input_name = $image_name;
            // имя полученного файла
            $img_output_name = time().'_'.$image_name;

            if(!file_exists($input_puth.$img_input_name)) {
                // исли нет файла в папке, записываем 1c_id в массив
                $arr_cat .= $image->{'1c_id'}.",";
                // остальное пропускаем
                continue;
            } else {
                // ресайзим
               self::imageHandler($img_input_name, $img_output_name, $input_puth, $output_puth, '', '40');
                // записываем имя полученного файла в БД
                \DB::table('categories')
                    ->where('1c_id', $image->{'1c_id'})
                    ->update(
                        [
                            'thumb_image' => $img_output_name,
                        ]
                    );
            }

        }
        // запоминаем 1c_id изображений, которые не прошли ресайзер
        if($arr_cat) {
            $time->arr_cat = substr($arr_cat, 0, -1);
        } else {
            $time->arr_cat = "";
        }
        //**********************************************************************


        // ресайз изображений товаров ******************************************

        // собираем 1c_id товаров, где имеются изображения
        $images = ItemImage::get(['item_1c_id'])->pluck('item_1c_id')->unique()->sort();

        // ресайзим и переносим в папку item-thumbs
        // путь где лежит исходный файл
        $input_puth = public_path().'/storage/item-images/';
        // путь куда положим полученный файл
        $output_puth = public_path().'/storage/item-thumbs/';

        $arr = "";
        foreach($images as $image) {
            // имя исходного файла
            $img_input_name = $image.'_0.jpg';
            // имя полученного файла
            $img_output_name = $image.'_'.time().'.jpg';

            if(!file_exists($input_puth.$img_input_name)) {
                // исли нет файла в папке, записываем 1c_id в массив
                $arr .= $image.",";
                // остальное пропускаем
                continue;
            } else {
                // ресайзим
               self::imageHandler($img_input_name, $img_output_name, $input_puth, $output_puth, '', '70');
                // записываем имя полученного файла в БД
                \DB::table('items')
                    ->where('1c_id', $image)
                    ->update(
                        [
                            'thumb_image' => $img_output_name,
                        ]
                    );
            }
        }
        // запоминаем 1c_id изображений, которые не прошли ресайзер
        if($arr) {
            $time->arr = substr($arr, 0, -1);
        } else {
            $time->arr = "";
        }
        //**********************************************************************

        // сохраняем в БД
        $time->save();


        return "Ресайзер отработал";
    }

    public static function index()
    {
        // берем данные
        $last_resize = ResizeTime::orderBy('date', 'desc')->first();

        // берем дату последнего ресайза
        $last_resize_date = $last_resize->date;


        // фиксируем временную метку текущего ресайза
        $date = date('Y-m-d H:i:s');

        // !!!******* путь к PUBLIC ******* !!!
        $public = realpath(__DIR__.'/../../../../').'/public_html';

        // ресайзим изображения категорий **********************************************

        // берем коды, которые не прошли ресайзинг в прошлый раз
        $cat_arr = $last_resize->arr_cat;
        $cat_arr = explode(",", $cat_arr);

        // берем изображения, которые обновились после последнего ресайза + те которые не прошли ресайз
        $cat_items = Category::where([['updated_at', '>', $last_resize_date], ['image_path', '!=', NULL]])->orWhereIn('1c_id', $cat_arr)->get(['1c_id', 'updated_at', 'image_path', 'thumb_image']);

        $arr_cat = "";

        if($cat_items->count()) {

            // ресайзим и переносим в папку catalog-thumbs

            // путь где лежит исходный файл
            $input_puth = $public.'/storage/item-images/';
            // путь куда положим полученный файл
            $output_puth = $public.'/storage/catalog-thumbs/';

            foreach($cat_items as $image) {

                // удаляем старый файл
                @unlink($output_puth.$image->thumb_image);

                // имя файла изображения
                $image_name = explode('/', trim($image->image_path))[1];
                // имя исходного файла
                $img_input_name = $image_name;
                // имя полученного файла
                $img_output_name = time().'_'.$image_name;

                if(!file_exists($input_puth.$img_input_name)) {
                    // исли нет файла в папке, записываем 1c_id в массив
                    $arr_cat .= $image->{'1c_id'}.",";
                    // остальное пропускаем
                    continue;
                } else {
                    // ресайзим
                    self::imageHandler($img_input_name, $img_output_name, $input_puth, $output_puth, '', '40');
                    // записываем имя полученного файла в БД

                    \DB::table('categories')
                        ->where('1c_id', $image->{'1c_id'})
                        ->update(
                            [
                                'thumb_image' => $img_output_name,
                            ]
                        );
                }
            }
        }

        if ($arr_cat) {
            $arr_cat = substr($arr_cat, 0, -1);
        } else {
            $arr_cat = "";
        }

        //*****************************************************************************


        // ресайзим изображения товаров ************************************************

        // берем коды, которые не прошли ресайзинг в прошлый раз
        $item_arr = $last_resize->arr;
        $item_arr = explode(",", $item_arr);

        // берем изображения, которые обновились после последнего ресайза + те которые не прошли ресайз
        $items = Item::where('updated_at', '>', $last_resize_date)->orWhereIn('1c_id', $item_arr)->get(['1c_id', 'updated_at', 'thumb_image']);

        $arr = "";
        if($items->count()) {

            // ресайзим и переносим в папку item-thumbs

            // путь где лежит исходный файл
            $input_puth = $public.'/storage/item-images/';
            // путь куда положим полученный файл
            $output_puth = $public.'/storage/item-thumbs/';

            foreach($items as $item) {

                // если товар имеет изображения
                if($item->images->count()) {
                    // выделяем окончание файла
                    $ext = explode('.', $item->images[0]->path_image)[1];
                    // имя исходного файла
                    $img_input_name = $item->{'1c_id'}.'_0.'.$ext;
                    // имя полученного файла
                    $img_output_name = $item->{'1c_id'}.'_'.time().'.jpg';

                    // удаляем старый файл
                    @unlink($output_puth.$item->thumb_image);

                    // удаляем старое имя из базы
                    \DB::table('items')
                        ->where('1c_id', $item->{'1c_id'})
                        ->update(
                            [
                                'thumb_image' => "",
                            ]
                        );

                    if(!file_exists($input_puth.$img_input_name)) {
                        // исли нет файла в папке, записываем 1c_id в массив
                        $arr .= $item->{'1c_id'}.",";
                    } else {
                        // ресайзим
                        self::imageHandler($img_input_name, $img_output_name, $input_puth, $output_puth, '', '70');

                        // записываем имя полученного файла в БД
                        \DB::table('items')
                            ->where('1c_id', $item->{'1c_id'})
                            ->update(
                                [
                                    'thumb_image' => $img_output_name,
                                ]
                            );
                    }
                }
            }
        }

        if ($arr) {
            $arr = substr($arr, 0, -1);
        } else {
            $arr = "";
        }

        //*****************************************************************************

        // обновляем время ресайза и список кодов не прошедших ресайз
        \DB::table('resize_times')->update(
            [
                'date' => $date,
                'arr_cat' => $arr_cat,
                'arr' => $arr,
            ]
        );

        return "Ресайзер index отработал";
    }

    // обработка изображений ***************************************************
    public static function imageHandler($img_input_name, $img_output_name, $input_path, $output_path, $width, $height) {
        // $img_input_name - имя исходного файла
        // $img_output_name - имя полученного файла
        // $input_path - путь где брать файл
        // $output_path - путь куда положить
        // $width - ширина для исходящего изобр, или пусто
        // $height - высота для исходящего изобр, или пусто

        if(!$width) $with = null;
        if(!$height) $height = null;

        $img_result = Image::make($input_path.$img_input_name)
            ->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($output_path.$img_output_name);
    }
    // **************************************************************************


}
