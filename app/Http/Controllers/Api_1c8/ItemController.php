<?php

namespace App\Http\Controllers\Api_1c8;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\XMLHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;

use App\Models\Item;
use App\Models\CharacteristicItem;
use App\Models\ItemImage;
use App\Models\ItemGuide;
use TCG\Voyager\Models\Setting;


class ItemController extends BaseController
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
        $requared_fields = ['id_1c', 'category_id_1c', 'name', 'is_component', ];

        // проверка на пустоту
        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        $data = $this->getItemData($this->xml_data);

        $img_input_name = trim($this->xml_data->image);

        $this->deleteOldImages($data['uuid']);

        // если есть изображение, обрабатываем
        if (!empty($img_input_name)) {

            if (!file_exists(config('ut.images_input_path') . $img_input_name)) {
                // вернем ошибку
                $response = new Response("Ошибка! Для товара {$data['uuid']} главное изображение в хранилище отсутствует! Повторите отправку! ".config('ut.images_input_path') . $img_input_name, 400);
                return $response;
            }

            $data = $this->resizeImages($img_input_name, $data);

        }

        try {
            $result = Item::updateOrCreate(['uuid' => $data['uuid']], $data);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return new Response($e->getMessage(), 400);
        }

        if ($this->xml_data->characteristics->characteristic->count()) {
            CharacteristicItem::where('item_id_1c', $data['id_1c'])->delete();
            foreach ($this->xml_data->characteristics->characteristic as $characteristic) {
                $this->characteristicItemStore($data, $characteristic);
            }
        }

        if ($result) {
            $response = new Response("Товар {$data['uuid']} добавлен(обновлен) успешно!", 200);
        }
        return $response;

    }

    public function postDelete(XMLHelper $XMLHelper, Request $request)
    {
        return new Response("Обработчик еще не создан!", 200);
    }

    public function itemFiles(XMLHelper $XMLHelper, Request $request)
    {
        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // поля в XML, которые должны быть заполнены
        $requared_fields = ['uuid',];

        // проверка на пустоту
        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        $item_uuid = trim($this->xml_data->uuid);
        $answer = "";

        if (isset($this->xml_data->images->image) && $this->xml_data->images->image->count()) {

            $this->deleteOldImages($item_uuid);

            ItemImage::where('item_uuid', $item_uuid)->delete();

            foreach ($this->xml_data->images->image as $image) {
                $img_input_name = trim($image);
                if (!file_exists(config('ut.images_input_path') . $img_input_name)) {
                    $answer .= "Изображение {$img_input_name} для товара {$item_uuid} в хранилище отсутствует!\n";
                    continue;
                }
                ItemImage::insert(array_merge(compact('item_uuid'), $this->resizeImages($img_input_name, [])));

                $answer .= "Изображение {$img_input_name} к товару {$item_uuid} добавлено успешно!\n";
            }
        }

        if (isset($this->xml_data->guides->guide) && $this->xml_data->guides->guide->count()) {
            ItemGuide::where('item_uuid', $item_uuid)->delete();

            foreach ($this->xml_data->guides->guide as $guide) {
                $answer .= $this->storeGuide($item_uuid, $guide)
                    ? "Инструкция {$guide} к товару {$item_uuid} добавлена успешно!\n"
                    : "Инструкция {$guide} для товара {$item_uuid} в хранилище отсутствует!\n";
            }
        }

        return new Response($answer, 200);
    }

    public function syncItems(XMLHelper $XMLHelper, Request $request)
    {
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        if ($this->xml_data->product->count() <= 0) {
            return new Response("Нет товаров для загрузки!", 400);
        }

        foreach ($this->xml_data->product as $product) {

//            $data = $this->getItemData($product);
            $data = $this->getData($product);
//            return new Response("Товары загружены!!!!!!", 200);

            $img_input_name = trim($product->image);

            if (!empty($img_input_name)) {
                if (file_exists(config('ut.images_input_path') . $img_input_name)) {
                    $data = $this->resizeImages($img_input_name, $data);
                }
            }

            try {
                $result = Item::create($data);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return new Response($e->getMessage(), 400);
            }

            if (isset($product->characteristics->characteristic) && $product->characteristics->characteristic->count()) {
                foreach ($product->characteristics->characteristic as $characteristic) {
                    $this->characteristicItemStore($data, $characteristic);
                }
            }

        }

       return new Response("Товары загружены!", 200);
    }

    public function currency(XMLHelper $XMLHelper, Request $request)
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
            'rate',
        ];

        // проверка на пустоту
        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        // id_1c - 001 - курс для опта
        // id_1c - 002 - курс для МРЦ

        // берем код
        $id_1c = trim($this->xml_data->id_1c);
        // берем курс
        $rate = trim($this->xml_data->rate);

        // остальные поля в xml пока игнорируем.

        // если опт
        if ($id_1c == "001") {

            // обновляем курс для опта
            Setting::where('id', 15)->update([
                'value' => $rate,
            ]);

            // берем uuid товаров с дисконтом
            $prod_uuids = Item::where('discount_str', '!=', null)->pluck('uuid')->toArray();

            // если не пусто
            if (count($prod_uuids)) {
                foreach ($prod_uuids as $item_uuid) {
                    // обновляем информацию о дисконте
                    $this->getCommonDiscounts($item_uuid);
                }
            }

            $response = new Response("Курс для валюты {$id_1c} обновлен", 200);

        } elseif ($id_1c == "002") { // если опт
            // обновляем курс для МРЦ
            Setting::where('id', 16)->update([
                'value' => $rate,
            ]);

            $response = new Response("Курс для валюты {$id_1c} обновлен", 200);

        } else {
            $response = new Response("Некорректное значение поля id_1c!", 200);
        }

        return $response;
    }

    public function syncFiles(XMLHelper $XMLHelper, Request $request)
    {
        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если в запросе есть товары
        if (!isset($this->xml_data->file) || $this->xml_data->file->count() <= 0) {
            $response = new Response("Ошибка! Нет данных в xml.", 400);
        }

        $error_uuid = array();

        foreach ($this->xml_data->file as $file) {

            $item = Item::where('uuid', trim($file->uuid))->first(['uuid']);

            if (!$item) {
                array_push($error_uuid, $item->uuid);

                if (isset($file->images->image) && $file->images->image->count()) {
                    foreach ($file->images->image as $image) {
                        @unlink(config('ut.images_input_path') . $image);
                    }
                }

                if (isset($file->guides->guide) && $file->guides->guide->count()) {
                    foreach ($file->guides->guide as $guide) {
                        @unlink(config('ut.images_input_path') . $guide);
                    }
                }

                continue;
            }

            // если есть изображения
            if (isset($file->images->image) && $file->images->image->count()) {
                foreach ($file->images->image as $image) {
                    $img_input_name = trim($image);
                    if (file_exists(config('ut.images_input_path') . $img_input_name)) {
                        ItemImage::insert(array_merge(['item_uuid' => $item->uuid], $this->resizeImages($img_input_name, [])));
                    }
                }
            }

            // обрабатываем инструкции, если имеются
            if (isset($file->guides->guide) && $file->guides->guide->count()) {
                foreach ($file->guides->guide as $guide) {
                    $this->storeGuide($item->uuid, $guide);
                }
            }
        }

        // формируем ответ
        $answer = "Пакет принят сайтом. Файлы синхронизированы успешно.";

        $err_resp = "";

        // если есть uuid, которых нет в базе
        if (count($error_uuid)) {

            // берем только уникальные
            $error_uuid = array_unique($error_uuid);

            // формируем ответ
            $answer = "Пакет принят сайтом. Не обработаны файлы для товаров: ";

            foreach ($error_uuid as $val) {
                $answer .= "\n" . $val . " ";
            }
        }

        return new Response($answer, 200);
    }

    public function postAddPrice(XMLHelper $XMLHelper, Request $request)
    {
        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если в запросе есть товары
        if (isset($this->xml_data->products->product) && $this->xml_data->products->product->count()) {

            // собираем ошибочные uuid
            $error_uuid = array();

            foreach ($this->xml_data->products->product as $product) {

                // берем uuid товара
                $uuid = $product->uuid;

                // берем товар
                $item = Item::where('uuid', $uuid)->first();

                // если нет
                if (!$item) {
                    // добавляем uuid к ошибочным
                    array_push($error_uuid, $uuid);

                    // пропускаем итерацию
                    continue;
                } else {

                    // берем тип цен
                    $type_price = trim($product->type_price);

                    // берем значение
                    $price_val = doubleval(trim($product->price));

                    if ($type_price == "price") {
                        // обновляем цену
                        $item->update(['price_usd' => $price_val]);
                    } elseif ($type_price == "price_rub") {
                        // обновляем цену
                        $item->update(['price_rub' => $price_val]);
                    } elseif ($type_price == "price_min") {
                        // обновляем цену
                        $item->update(['price_min_usd' => $price_val]);
                    } elseif ($type_price == "price_min_rub") {
                        // обновляем цену
                        $item->update(['price_min_rub' => $price_val]);
                    } elseif ($type_price == "price_mr") {
                        // обновляем цену
                        $item->update(['price_mr_usd' => $price_val]);
                    } elseif ($type_price == "price_mr_rub") {
                        // обновляем цену
                        $item->update(['price_mr_rub' => $price_val]);
                    } elseif ($type_price == "service") {
                        // обновляем нормо-час
                        $item->update(['norm_hour' => $price_val]);
                    }

                    // обновляем информацию о дисконте у товара
                    $this->getCommonDiscounts($uuid);

                }
            }

            // формируем ответ
            $answer = "Пакет принят сайтом. Цены установлены успешно.";

            $err_resp = "";

            // если есть uuid, которых нет в базе
            if (count($error_uuid)) {

                // берем только уникальные
                $error_uuid = array_unique($error_uuid);

                // формируем ответ
                $answer = "Пакет принят сайтом. Не обработаны цены на товары: ";

                foreach ($error_uuid as $val) {
                    $answer .= "\n" . $val . " ";
                }

            }

            $response = new Response($answer, 200);

        } else {
            $response = new Response("Ошибка! Нет данных в xml.", 200);
        }

        return $response;

    }

    public function postAddCount(XMLHelper $XMLHelper, Request $request)
    {
        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если в запросе есть товары
        if (!isset($this->xml_data->products->product) || $this->xml_data->products->product->count() <= 0) {
            return new Response("Ошибка! Нет данных в xml.", 400);
        }

        $error_uuid = array();

        //TODO вероятно сделать выборку по whereIN
        foreach ($this->xml_data->products->product as $product) {
            $item = Item::where('uuid', $product->uuid)->first();
            if (!$item) {
                array_push($error_uuid, $product->uuid);
                continue;
            }
            $item->update($this->getAmountData($product));
        }

        // формируем ответ
        $answer = "Пакет принят сайтом. Количества установлены успешно.";

        // если есть uuid, которых нет в базе
        if (count($error_uuid)) {
            $answer .= " Не обработаны количества для товаров: " . implode("\n", array_unique($error_uuid));
        }

        return new Response($answer, 200);
    }

    public function postTruncatePrice(XMLHelper $XMLHelper, Request $request): Response
    {
        $this->xml_data = $XMLHelper->get_xml($request);
        if ($response = $this->xml_auth($request)) {
            return $response;
        }
        if (isset($this->xml_data->delete) && empty(trim($this->xml_data->delete))) {
            Item::query()->update([
                'price_usd' => 0,
                'price_rub' => 0,
                'price_min_usd' => 0,
                'price_min_rub' => 0,
                'price_mr_usd' => 0,
                'price_mr_rub' => 0,
            ]);
            $response = new Response("Обнуление цен произведено успешно.", 200);
        } else {
            $response = new Response("Обнуление цен НЕ произведено. Тег delete отсутствует или не пустой.", 200);
        }
        return $response;
    }

    public function postTruncateAmount(XMLHelper $XMLHelper, Request $request): Response
    {
        $this->xml_data = $XMLHelper->get_xml($request);
        if ($response = $this->xml_auth($request)) {
            return $response;
        }
        if (isset($this->xml_data->delete) && empty(trim($this->xml_data->delete))) {
            Item::query()->update($this->getAmountData());
            $response = new Response("Обнуление количеств произведено успешно.", 200);
        } else {
            $response = new Response("Обнуление количеств НЕ произведено. Тег delete отсутствует или не пустой.", 200);
        }
        return $response;
    }

    private function getAmountData($product = null): array
    {
        return [
            'amount' => $product ? $product->amount : 0,
            'reserve' => $product ? $product->reserve : 0,
            'locked' => $product ? $product->locked : 0,
            'expected' => $product ? $product->expected : 0,
            'expected_date' => $product ? $product->expected_date : null,
        ];
    }

    private function getData(object $data): array
    {
        $data = $this->prepareData($data);
        if ($data['type'] == 'edition') {
            $data['id_1c'] = $data['id_1c'] . "-" . $data['edition'];
        }
        $data['analogue_container_uuid'] = $data['analogs'] ?? null;
        return Arr::only($data, app(Item::class)->getFillable());
    }


    public function getItemData($product): array
    {
        $type = trim($product->type);
        $edition = intval(trim($product->edition));
        $id_1c = trim($product->id_1c);
        if ($type == 'edition') {
            $id_1c = $id_1c . "-" . $edition;
        }

        $category_id = intval(trim($product->category_id_1c));
        $data = array(
            'is_component' => intval($product->is_component),
            'in_archive' => intval(trim($product->in_archive)),
            'in_price' => intval(trim($product->in_priсe)),
            // влияет только на архивные
            'id_1c' => $id_1c,
            'uuid' => trim($product->uuid),
            'category_id_1c' => $category_id,
            'type' => $type,
            'edition' => $edition,
            'name' => trim($product->name),
            'content' => trim($product->content),
            'more_about' => trim($product->more_about),
            'vendor_code' => trim($product->vendor_code),
            'barcode' => trim($product->barcode),
            'codeTNVD' => trim($product->codeTNVD),
            'country' => trim($product->country),
            'apply' => trim($product->apply),
            'shelf_life' => trim($product->life_time),
            'guarantee_period' => intval($product->guarantee_period),
            'equipment' => trim($product->equipment),
            'weight' => !empty(trim($product->weight)) ? trim($product->weight) : 0,
            'netto' => !empty(trim($product->netto)) ? trim($product->netto) : 0,
            'width' => !empty(trim($product->width)) ? trim($product->width) : 0,
            'depth' => !empty(trim($product->depth)) ? trim($product->depth) : 0,
            'height' => !empty(trim($product->height)) ? trim($product->height) : 0,
            'packaging' => intval(trim($product->packaging)),
            'youtube' => trim($product->video),
            'brand_uuid' => !empty(trim($product->brand_uuid)) ? trim($product->brand_uuid) : null,
            'factory' => trim($product->factory),
            'is_new_item' => intval(trim($product->is_new_product)),
            'date_new_item' => $this->getOrDefault('date_new_product', null),
            'buy_with' => trim($product->buy_with),
            'forget_buy' => trim($product->forget_buy),
            'cheap_goods' => trim($product->cheap_goods),
            'certificate' => trim($product->cert_name),
            'certificate_exp' => $this->getOrDefault('cert_end_date', null),
            'synonyms' => trim($product->synonyms),
            'schemes' => trim($product->schemes),
            'importer' => intval(trim($product->importer)),
            'adjustable' => intval(trim($product->adjustable)),
            'spec_price' => intval(trim($product->spec_price)),
            'analogue_container_uuid' => !empty(trim($product->analogs)) ? trim($product->analogs) : null,
            'image' => '',
            'image_mid' => '',
            'image_sm' => '',
            // 'is_action' => intval(trim($this->xml_data->is_action)),
            // 'date_open_action' => $date_open_action,
            // 'mini_text' => trim($this->xml_data->mini_text),
            // 'discounted' => $discounted,
            // 'count' => intval(trim($this->xml_data->count)),
            // 'count_type' => intval(trim($this->xml_data->count_type)),
            // 'count_text' => trim($this->xml_data->count_text),
        );
        return $data;
    }

    private function resizeImages(string $img_input_name, array $data): array
    {
        $edition_prefix = $data['edition'] ? '-' . $data['edition'] : '';

        $data['image'] = explode('.', $img_input_name)[0] . $edition_prefix . '.jpg';
        $data['image_mid'] = explode('.', $img_input_name)[0] . $edition_prefix . '_mid_' . time() . '.jpg';
        $data['image_sm'] = explode('.', $img_input_name)[0] . $edition_prefix . '_sm_' . time() . '.jpg';

        $this->imageHandler($img_input_name, $data['image'], config('ut.images_input_path'), config('ut.images_items_path'), '1600', '');
        $this->imageHandler($img_input_name, $data['image_mid'], config('ut.images_input_path'), config('ut.images_items_path'), '', '300');
        $this->imageHandler($img_input_name, $data['image_sm'], config('ut.images_input_path'), config('ut.images_items_path'), '', '70');

        @unlink(config('ut.images_input_path') . $img_input_name);

        return $data;
    }

    private function deleteOldImages(string $uuid): void
    {
        $old_images = Item::where('uuid', $uuid)->first(['image', 'image_mid', 'image_sm']);
        if ($old_images) {
            @unlink(config('ut.images_items_path') . $old_images->image);
            @unlink(config('ut.images_items_path') . $old_images->image_mid);
            @unlink(config('ut.images_items_path') . $old_images->image_sm);
        }
    }

    private function storeGuide(string $item_uuid, mixed $guide): bool
    {
        $isExists = file_exists(config('ut.images_input_path') . $guide);

        if($isExists) {
            ItemGuide::insert([
                'item_uuid' => $item_uuid,
                'file' => $guide,
            ]);
            rename(config('ut.images_input_path') . $guide, config('ut.images_guides_path') . $guide);
        }
        return $isExists;
    }

    private function characteristicItemStore(array $data, mixed $characteristic): void
    {
        CharacteristicItem::insert([
            'item_id_1c' => $data['id_1c'],
            'item_uuid' => $data['uuid'],
            'characteristic_uuid' => trim($characteristic->uuid),
            'value' => trim($characteristic->value),
        ]);
    }

}
