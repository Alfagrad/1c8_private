<?php

namespace App\Http\Controllers\Api_1c8;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Helpers\XMLHelper;
use Illuminate\Filesystem\Filesystem;

use App\Models\AnalogueContainer;
use App\Models\ItemAnalogue;
use App\Models\Scheme;
use App\Models\SchemeItem;

class SparePartController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function postAnalogueCreateOrUpdate(XMLHelper $XMLHelper, Request $request)
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

        // записываем данные из xml в data
        $data = array(
            'uuid' => trim($this->xml_data->uuid),
            'name' => trim($this->xml_data->name),
        );

        $uuid = trim($this->xml_data->uuid);

        if ($this->xml_data->analogs && $this->xml_data->analogs->analog->count()) {

            // удаляем имеющиеся аналоги
            ItemAnalogue::where('container_uuid', $uuid)->delete();

            // переписываем заново
            foreach ($this->xml_data->analogs->analog as $analog) {

                $data_an = array(
                    'container_uuid' => $uuid,
                    'analog_uuid' => trim($analog),
                );

                ItemAnalogue::insert($data_an);
            }
        } else {
            $response = new Response("Ошибка! Список аналогов в контейнере {$uuid} пуст! Повторите отправку!", 200);
            return $response;
        }

        $result = AnalogueContainer::updateOrCreate(['uuid' => $uuid], $data);

        if($result) {
            $response = new Response("Контейнер аналогов {$uuid} добавлен(обновлен) успешно!", 200);
        } else {
            $response = new Response("Ошибка! Контейнер аналогов {$uuid} НЕ добавлен! Повторите отправку!", 200);
        }

        return $response;
    }

    public function postSchemeCreateOrUpdate(XMLHelper $XMLHelper, Request $request)
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

        // берем код схемы
        $uuid = trim($this->xml_data->uuid);

        // берем имя изображения
        $image = trim($this->xml_data->image);

        // записываем данные из xml в data
        $data = array(
            'uuid' => $uuid,
            'name' => trim($this->xml_data->name),
        );

        // берем имя изображение
        $img_input_name = trim($this->xml_data->image);
        // путь где лежит изображение
        $input_puth = '/home/alfastok/public_html/storage/ut_1c8/common/';
        // путь куда положим полученный файл
        $output_puth = '/home/alfastok/public_html/storage/ut_1c8/scheme-images/';

        // если есть изображение, обрабатываем
        if(!empty($img_input_name)) {

            // имя полученного файла
            $img_output_name = explode('.', $img_input_name)[0].'_'.time().'.jpg';

            // проверяем, есть ли изображение в хранилище
            // если нет
            if(!file_exists($input_puth.$img_input_name)) {

                // вернем ошибку
                $response = new Response("Ошибка! Для схемы {$uuid} изображение в хранилище отсутствует! Повторите отправку!", 200);
                return $response;
            } else {

                // ресайзим и переносим изображение
                $this->imageHandler($img_input_name, $img_output_name, $input_puth, $output_puth, '2000', '');

                // берем имя старых изображений
                $old_images = Scheme::where('uuid', $uuid)->first(['image']);

                // если не пусто
                if($old_images){
                    // удаляем старые изображения
                    @unlink($output_puth.$old_images->image);
                }

                // удаляем входящее изображение из хранилища
                @unlink($input_puth.$img_input_name);

                // добавляем в дату имена файлов
                $data['image'] = $img_output_name;
            }
        } else { // если в xml нет имени картинки

            // берем имя старых изображений
            $old_images = Scheme::where('uuid', $uuid)->first(['image']);

            // если не пусто
            if($old_images){
                // удаляем старые изображения
                @unlink($output_puth.$old_images->image);
            }

            // добавляем в дату пустые имена файлов
            $data['image'] = "";
        }

        if ($this->xml_data->spares && $this->xml_data->spares->item->count()) {

            // удаляем имеющиеся элементы схемы
            SchemeItem::where('scheme_uuid', $uuid)->delete();

            // переписываем заново
            foreach ($this->xml_data->spares->item as $item) {

                $data_ch = array(
                    'scheme_uuid' => $uuid,
                    'spare_name' => trim($item->spare),
                    'position' => intval($item->position),
                    'product_uuid' => trim($item->product),
                    'amount' => intval($item->amount),
                );

                SchemeItem::insert($data_ch);
            }
        }

        $result = Scheme::updateOrCreate(['uuid' => $uuid], $data);

        if($result) {
            $response = new Response("Схема {$uuid} добавлена(обновлена) успешно!", 200);
        } else {
            $response = new Response("Ошибка! Схема {$uuid} НЕ добавлена! Повторите отправку!", 200);
        }

        return $response;
    }

    public function sсhemesFirstUpload(XMLHelper $XMLHelper, Request $request)
    {

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если в запросе есть схемы
        if ($this->xml_data->scheme->count()) {

            // очищаем таблицы в БД
            Scheme::truncate();
            SchemeItem::truncate();

            // путь где лежат изображения
            $input_puth = '/home/alfastok/public_html/storage/ut_1c8/common/';
            // путь куда положим полученные файлы
            $output_puth = '/home/alfastok/public_html/storage/ut_1c8/scheme-images/';

            // очищаем папку с изображениями категорий
            $file = new Filesystem;
            $file->cleanDirectory($output_puth);

             // обрабатываем
            foreach ($this->xml_data->scheme as $scheme) {

                // берем код схемы
                $uuid = trim($scheme->uuid);

                // записываем данные из xml в data
                $data = array(
                    'uuid' => $uuid,
                    'name' => trim($scheme->name),
                );

                // берем имя изображения
                $img_input_name = trim($scheme->image);

                // если есть изображение, обрабатываем
                if(!empty($img_input_name)) {

                    // имя полученного файла
                    $img_output_name = explode('.', $img_input_name)[0].'_'.time().'.jpg';

                    // проверяем, есть ли изображение в хранилище
                    // если нет
                    if(!file_exists($input_puth.$img_input_name)) {

                        // добавляем в дату пустые имена файлов
                        $data['image'] = "";

                    } else {

                        // ресайзим и переносим изображение
                        $this->imageHandler($img_input_name, $img_output_name, $input_puth, $output_puth, '2000', '');

                        // удаляем входящее изображение из хранилища
                        @unlink($input_puth.$img_input_name);

                        // добавляем в дату имена файлов
                        $data['image'] = $img_output_name;
                    }
                } else { // если в xml нет имени картинки

                    // добавляем в дату пустое имя файла
                    $data['image'] = "";
                }

                Scheme::create($data);

                // если есть запчасти
                if ($scheme->spares && $scheme->spares->item->count()) {

                    // добавляем
                    foreach ($scheme->spares->item as $item) {

                        $data_ch = array(
                            'scheme_uuid' => $uuid,
                            'spare_name' => trim($item->spare),
                            'position' => intval($item->position),
                            'product_uuid' => trim($item->product),
                            'amount' => intval($item->amount),
                        );

                        SchemeItem::insert($data_ch);
                    }
                }
            }

            $response = new Response("Схемы загружены!", 200);
        } else {
            $response = new Response("Нет схем для загрузки!", 200);
        }

        return $response;

    }

    public function analogsFirstUpload(XMLHelper $XMLHelper, Request $request)
    {

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если в запросе есть аналоги
        if ($this->xml_data->analog->count()) {

            // очищаем таблицы в БД
            ItemAnalogue::truncate();
            AnalogueContainer::truncate();

             // обрабатываем
            foreach ($this->xml_data->analog as $container) {

                // берем код контейнера
                $uuid = trim($container->uuid);

                // записываем данные из xml в data
                $data = array(
                    'uuid' => $uuid,
                    'name' => trim($container->name),
                );

                // записываем
                AnalogueContainer::create($data);

                // если есть в контейнере аналоги
                if ($container->analogs && $container->analogs->analog->count()) {

                    // добавляем
                    foreach ($container->analogs->analog as $analog) {

                        $data_con = array(
                            'container_uuid' => $uuid,
                            'analog_uuid' => trim($analog),
                        );

                        ItemAnalogue::insert($data_con);
                    }
                }
            }

            $response = new Response("Контейнеры с аналогами загружены!", 200);
        } else {
            $response = new Response("Нет контейнеров с аналогами для загрузки!", 200);
        }

        return $response;
    }
}
