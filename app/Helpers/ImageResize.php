<?php
/**
@VERSION: 0.4, для Voyager
 */

namespace App\Helpers;

use App\Item;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class ImageResize {

    // Переделано под админку
    // Файлы храняться в public/storage/
    // Приходит в формате news/img.jpg
    public function resize($filePath, $height = null, $width = null){
        if(!$filePath){
            return 'upload/no-thumb.png';
        }

        $storage = Storage::disk('public');
        if($storage->exists($filePath)){
            $filePrefix  = '';

            if(!$height and !$width){
                return $storage->url($filePath);
            }
            if($height){
                $filePrefix .= 'h'.$height.'_';
            }
            if($width){
                $filePrefix .= 'w'.$width.'_';
            }

            $fileAndFolder = explode('/', $filePath);

            $thumbPath = $fileAndFolder[0]. '/' . 'thumb/'. $filePrefix. $fileAndFolder[1];

            if($storage->exists($thumbPath)){
                return $storage->url($thumbPath);
            } else {
                if(!$storage->exists($fileAndFolder[0]. '/' . 'thumb/')){
                    $storage->makeDirectory($fileAndFolder[0]. '/' . 'thumb/');
                }
				//return $filePath;
                $img = Image::make('storage/' . $filePath);

                $img->resize($height, $width,  function ($constraint) {
                    $constraint->aspectRatio();
                });

                $img->save('storage/' . $thumbPath);
                return $storage->url($thumbPath);
            }

        }
            return '/upload/no-thumb.png';

    }
}