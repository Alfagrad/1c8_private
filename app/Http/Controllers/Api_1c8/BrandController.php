<?php

namespace App\Http\Controllers\Api_1c8;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Helpers\XMLHelper;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

use App\Models\Brand;

class BrandController extends BaseController
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
            'uuid',
            'name',
        ];

        // проверка на пустоту
        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        // берем uuid
        $uuid = trim($this->xml_data->uuid);

        // берем изображение
        $image = trim($this->xml_data->image);

        // записываем данные из xml в data
        $data = array(
            'uuid' => $uuid,
            'name' => trim($this->xml_data->name),
            'site' => trim($this->xml_data->site),
        );

        // путь где лежит изображение
        $input_puth = '/home/alfastok/public_html/storage/ut_1c8/common/';
        // путь куда положим полученный файл
        $output_puth = '/home/alfastok/public_html/storage/ut_1c8/brand-logo/';

        // если есть изображение, обрабатываем
        if(!empty($image)) {

            // проверяем, есть ли изображение в хранилище
            // если нет
            if(!file_exists($input_puth.$image)) {

                // вернем ошибку
                $response = new Response("Ошибка! Для бренда {$uuid} изображение в хранилище отсутствует! Повторите отправку!", 200);

                return $response;

            } else {

                // переносим изображение
                rename($input_puth.$image, $output_puth.$image);

                // добавляем в дату имя файла
                $data['image'] = $image;

            }
        } else { // если в xml нет имени картинки

            // добавляем в дату пустое имя файла
            $data['image'] = "";
        }


        $result = Brand::updateOrCreate(['uuid' => $uuid], $data);

        if($result) {
            $response = new Response("Бренд {$uuid} добавлен(обновлен) успешно!", 200);
        } else {
            $response = new Response("Ошибка! Бренд {$uuid} НЕ добавлен! Повторите отправку!", 200);
        }

        return $response;
    }

    public function firstUpload(XMLHelper $XMLHelper, Request $request)
    {

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если в запросе есть бренды
        if ($this->xml_data->brand->count()) {

            // очищаем таблицу в БД
            $res = Brand::query()->delete();

            // путь где лежат изображения
            $input_puth = '/home/alfastok/public_html/storage/ut_1c8/common/';
            // путь куда положим полученные файлы
            $output_puth = '/home/alfastok/public_html/storage/ut_1c8/brand-logo/';

            // очищаем папку с изображениями категорий
            $file = new Filesystem;
            $file->cleanDirectory($output_puth);

            // обрабатываем
            foreach ($this->xml_data->brand as $brand) {

                // берем код бренда
                $uuid = trim($brand->uuid);

                // берем изображение
                $image = trim($brand->image);

                // записываем данные из xml в data
                $data = array(
                    'uuid' => $uuid,
                    'name' => trim($brand->name),
                    'site' => trim($brand->site),
                );

                // путь где лежит изображение
                $input_puth = '/home/alfastok/public_html/storage/ut_1c8/common/';
                // путь куда положим полученный файл
                $output_puth = '/home/alfastok/public_html/storage/ut_1c8/brand-logo/';

                // если есть изображение, обрабатываем
                if(!empty($image)) {

                    // проверяем, есть ли изображение в хранилище
                    // если нет
                    if(!file_exists($input_puth.$image)) {

                        // добавляем в дату пустое имя файла
                        $data['image'] = "";

                    } else {

                        // переносим изображение
                        rename($input_puth.$image, $output_puth.$image);

                        // добавляем в дату имя файла
                        $data['image'] = $image;

                    }
                } else { // если в xml нет имени картинки

                    // добавляем в дату пустое имя файла
                    $data['image'] = "";
                }

                // добавляем
                Brand::create($data);
            }

            $response = new Response("Бренды загружены!", 200);

        } else {
            $response = new Response(" Нет брендов для загрузки!", 200);
        }

        return $response;
    }

}
