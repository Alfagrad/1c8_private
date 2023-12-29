<?php


namespace App\Http\Controllers;


use App\Jobs\Resize;
use Illuminate\Support\Facades\Artisan;
use Intervention\Image\Facades\Image;


class ResizeController
{

    public $pathLocal = '';
    public $pathServer = '/../../public_html/';
    public $path = '';
    public $images = [];

    public function __construct()
    {
        $this->path = $this->pathLocal;
    }

    public function resizeCategory()
    {

        $scandir = scandir(public_path('storage/item-images'));
        foreach ($scandir as $image) {
            $this->category($image);
        }
        $this->delete();
    }

    public function resizeItems()
    {


        $scandir = scandir(public_path('storage/item-images'));
        foreach ($scandir as $image) {
            //получение всех картинок нужных для сжатия
            $this->getImages($image);
        }
        // проверка на первые картинки товара для каталога
        $this->items();

    }

    public function scan($param = true)
    {
        try {
            $this->resizeItems();
            $this->resizeCategory();
            if($param){
                return redirect('/admin');
            }
        } catch (\Exception $exception) {
            if($param){
                return redirect('/admin');
            }
        }
    }

    public function delete()
    {

        $filesCategory = glob(public_path($this->pathServer . 'storage/item-icons/*')); //get all file names
        foreach ($filesCategory as $file) {
            if (is_file($file))
                unlink($file); //delete file
        }
    }

    public function getImages($image)
    {
        if (strripos($image, '_0.jpg')) {  //отберем только те файлы что заканчиваются на _0.jpg
            $this->images[] = $image;
        }
    }


    public function items()
    {
        foreach ($this->images as $image) {
            if (strripos($image, '_0.jpg')) {  //отберем только те файлы что заканчиваются на _0.jpg
                $this->images[] = $image;
                //проверка файла на дату создания
                $dataFile = stat(public_path('storage/item-images/' . $image));

                //далее будем искать такой файл в новой папке
                if (file_exists(public_path('storage/item-icons/' . $image))) {

                    $dataFileResize = stat(public_path('storage/item-icons/' . $image));

                    //данные о файле получили теперь сверим актуальный ли файл

                    if ($dataFile['ctime'] > $dataFileResize['ctime']) {


                        //удаляем старую картинку и обрезаем новую
                        unlink(public_path('storage/item-icons/' . $image));
                        $this->saveResize($image);
                    }
                } else {

                    $this->saveResize($image);
                }
            }
        }

    }


    public function category($image)
    {
        if (preg_match("/^(c)([0-9]*).jpg/", $image)) {  //отберем только те файлы категорий
            try {
                Image::make(public_path($this->path . 'storage/item-images/' . $image))->resize(null, 60, function($constraint) {
                   $constraint->aspectRatio();
                })->save(public_path($this->path . 'storage/categories/' . $image));

            } catch (\Exception $exception) {

            }
        }

    }


    public function saveResize($image)
    {


        try {
            $imageResize = Image::make(public_path('storage/item-images/' . $image));
            $imageResize->resize(null, 100, function($constraint){
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save(public_path('storage/item-icons/' . $image));
        } catch (\Exception $exception) {

        }
    }
}
