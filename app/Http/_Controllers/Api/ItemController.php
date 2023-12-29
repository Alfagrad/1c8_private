<?php

namespace App\Http\Controllers\Api;

use App\CharacteristicItem;
use App\Item;
use App\ItemAction;
use App\ItemImage;
use App\Scheme;
use App\SchemeParts;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Helpers\XMLHelper;
use Illuminate\Http\Response;

class ItemController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * @description
     * @param XMLHelper $XMLHelper
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreateOrUpdate(XMLHelper $XMLHelper)
    {
        //dd($this->xml);

        $data['title'] = 'Добавление и обновление товара';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/items/createOrUpdate';

        $data['desc'] = '<p>Описание полей:<br>
        <ul>
            <li><b>id_1c</b> - ID категории из 1С. <b>INT, Обязательное</b></li>
            <li><b>category_id_1c</b> - Идентификатор категории из 1С. <b>INT, Обязательное</b></li>
            <li><b>name</b> - Название категории. <b>STRING, Обязательное</b></li>

            <li><b>description</b> - Краткое описание. <b>TEXT</b></li>
            <li><b>code</b> - Код, артикул товара. <b>STRING, Обязательное</b></li>
            <li><b>content</b> - Большое описание товара. <b>TEXT</b></li>
            <li><b>more_about</b> - Небольшой текст для блока: Подробнее о товаре (i)  <b>Обязательное</b></li>
            <li><b>mini_text</b> - Небольшой текст для блока со скидкой, дополнительно к ней  <b></b></li>


            <li><b>weight</b> - Вес <b>FLOAT, Обязательное</b></li>
            <li><b>brand</b> - Бренд <b>STRING, Обязательное</b></li>

            <li><b>is_action</b>Есть ли акции? <b>0 или 1</b></li>
            <li><b>discounted</b> - Скидка, по схеме: 5-120;10-100, количество-цена;</li>
            <li><b>as_gift</b> - ID товара который идет как подарок при покупке<b>STRING, Обязательное или пустая строка</b></li>

            <li><b>buy_with</b> - ID разделов которые покупают с товаром, через запятую <b>STRING, Обязательное или пустая строка</b></li>
            <li><b>forget_buy</b> - ID Товаров не забудьте купить с этом товаром, через запятую. <b>STRING, Обязательное или пустая строка</b></li>
            <li><b>cheap_goods</b> - ID уцененных товаров, через запятую. <b>STRING, Обязательное или пустая строка</b></li>


            <li><b>count</b> - Количество товара. <b>INT, Число, может быть 0</b></li>
            <li><b>count_type</b> Может быть 4 типа: 1 - В наличии, 2 - Резерв, 3 - Поступит, 4 - Нет <b>INT, Обязательное</b></li>
            <li><b>count_text</b>Указываем дату для разерва или поступления <b>STRING, Может быть пустой строкой</b></li>


            <li><b>price_usd</b> - Дилерская цена  товара в USD <b>FLOAT, Обязательное</b></li>
            <li><b>price_usd_2</b> - Дилерская цена  товара в USD <b>FLOAT, Обязательное</b></li>
            <li><b>price_mr_usd</b> - Цена минимальной раализации в USD. <b>FLOAT, Обязательное</b></li>
            <li><b>price_min_usd</b> - Минимальная цена товара в USD <b>FLOAT, Обязательное</b></li>

            <li><b>popular</b> - Популярность <b>INT</b></li>
            <li><b>default_sort</b> - Сортировка по умолчанию <b>STRING 100</b></li>


            <li><b>characteristics</b> -  Характеристики товара<b></b></li>
            <li><b>characteristic</b> Блок с характристиками<b></b>
                <ul>
                   <li><b>char_id_1c</b> - id характеристики <b>INT, Обязательное</b></li>
                   <li><b>char_value</b> - значение  <b>STRING, Обязательное</b></li>
                </ul>
            </li>

            <!--
            <li><b>actions</b> - Акции товара<b></b></li>
            <li><b>action</b> - Блок с акциями<b></b>
                <ul>
                   <li><b>count</b> - Количество <b>INT, Обязательное</b></li>
                   <li><b>discount</b> - скидка в рублях<b>FLOAT, Обязательное</b></li>
                </ul>
            </li>
            -->
            <!-- По сути можно офрмить тегами -->
            <li>images<b></b> - Изображение<b></b></li>
            <li><b>image</b>Изображение<b></b>
                <ul>
                   <li><b>path</b> Имя изображения <b></b></li>
                </ul>
            </li>

        </ul>
        </p>';


        $this->xml->addChild('login', $this->login);
        $this->xml->addChild('password', $this->password);

        $this->xml->addChild('id_1c', '44511');
        $this->xml->addChild('category_id_1c', '44511');

        $this->xml->addChild('name', 'Кабель ПВХ 11-23');
        $this->xml->addChild('description', 'Краткое описание кабеля');
        $this->xml->addChild('code', 'A112341');
        $this->xml->addChild('content', '<![CDATA[<p>Cтатья о нашем товаре</p>]]>');
        $this->xml->addChild('more_about', 'Описание для ...');
        $this->xml->addChild('mini_text', 'Мини текст для ...');

        $this->xml->addChild('weight', '1.22');
        $this->xml->addChild('brand', 'Sony');


        $this->xml->addChild('is_action', 1);
        $this->xml->addChild('discounted', '5-120;10-100');
        $this->xml->addChild('as_gift', '456434,234432,456324');

        $this->xml->addChild('buy_with', '678423,678421,678424');
        $this->xml->addChild('forget_buy', '3456342,3456343,3456345');
        $this->xml->addChild('cheap_goods', '3456342,3456343,3456345');

        $this->xml->addChild('count', '0');
        $this->xml->addChild('count_type', '2'); // Описать типы
        $this->xml->addChild('count_text', '15.05.2018'); // По сути можно и в unix

        $this->xml->addChild('price_usd', '175');
        $this->xml->addChild('price_usd_2', '275');
        $this->xml->addChild('price_mr_usd', '250');
        $this->xml->addChild('price_min_usd', '270');

        $this->xml->addChild('popular', 0);
        $this->xml->addChild('default_sort', 1);


        $characteristics = $this->xml->addChild('characteristics');
        $characteristic = $characteristics->addChild('characteristic');
        $characteristic->addChild('id_1c', '1000000');
        $characteristic->addChild('value', 'Сталь');
        $characteristic->addChild('type', 'text'); // Тип данных - text, bool, int, float
        $characteristic->addChild('unit', ''); //


        $characteristic = $characteristics->addChild('characteristic');
        $characteristic->addChild('id_1c', '1000001');
        $characteristic->addChild('value', '100');


        //$characteristics = $this->xml->addChild('actions');
        //$characteristic  = $characteristics->addChild('action');
        //$characteristic->addChild('count', '5');
        //$characteristic->addChild('discount', '2.00');

        $characteristics = $this->xml->addChild('images');
        $characteristic = $characteristics->addChild('image');
        $characteristic->addChild('path', 'image.png');
        $characteristic = $characteristics->addChild('image');
        $characteristic->addChild('path', 'image2.png');


        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);

    }

    /**
     * @description Обновление или создания товара
     * @param XMLHelper $XMLHelper
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function postCreateOrUpdate(XMLHelper $XMLHelper, Request $request)
    {

        // Обязательные поля которые должны прийти в XML
        $requared_fields = [
            'id_1c',
            'category_id_1c',
            'name',
            'code',
            'weight',
            'count',
            'count_type',
            'price_usd',
            'price_usd_2',
            'price_mr_usd',
            'price_min_usd',
            'date_of_last_price_change',
            'is_component',
        ];
        //description content

        $this->xml_data = $XMLHelper->get_xml($request);
        if ($response = $this->xml_auth($this->xml_data, $this->xml)) {
            return $response;
        };

        $this->valid_empty($requared_fields);

        // преобразуем дату сертификата в "съедобный" формат
        if($this->xml_data->certificate->sert_end_day_array) {
            $sert_end_day_array = explode('/', trim($this->xml_data->certificate->cert_end_date));
            $sert_end_day = $sert_end_day_array[2]."-".$sert_end_day_array[0]."-".$sert_end_day_array[1];
        } else $sert_end_day = '';

        // преобразуем дату новинки в "съедобный" формат
        if(trim($this->xml_data->date_new_item)) {
            $date_new_item_array = explode('/', trim($this->xml_data->date_new_item));
            $date_new_item = $date_new_item_array[2]."-".$date_new_item_array[0]."-".$date_new_item_array[1];
        } else $date_new_item = '';

        // преобразуем дату обновления цен в "съедобный" формат
        if(trim($this->xml_data->date_of_last_price_change)) {
            $date_change_price_array = explode('/', trim($this->xml_data->date_of_last_price_change));
            $date_change_price = $date_change_price_array[2]."-".$date_change_price_array[0]."-".$date_change_price_array[1];
        } else $date_change_price = '';

        // установка даты начала акции, если товар акционный или со скидкой
        // запрос к бд
        $current_data = Item::where('1c_id', intval(trim($this->xml_data->id_1c)))->first(['discounted', 'date_open_action']);
        // берем новый discounted
        $discounted = trim($this->xml_data->discounted);
        // если обновление товара
        if($current_data) {
            // берем текущие значения полей discounted, date_open_action
            $current_discounted = trim($current_data->discounted);
            $current_action_date = $current_data->date_open_action;
            // сравниваем
            if(empty($discounted) && $current_discounted == '0') { // если пришел пустой discounted и текущий '0', обнуляем дату акции
                $date_open_action = "0000-00-00";
            } elseif ($current_discounted == $discounted) { // если discounted не изменился, оставляем дату акции без изменений
                $date_open_action = $current_action_date;
            } else { // если discounted изменился, устанавливаем текущую дату для акции
                $date_open_action = date('Y-m-d');
            }
        } else { // если новый товар
            if(empty($discounted)) {
                $date_open_action = "0000-00-00";
            } else {
                $date_open_action = date('Y-m-d');
            }
        }

        // синонимы
        if(isset($this->xml_data->synonyms)) {
            $synonyms = trim($this->xml_data->synonyms);
        } else {
            $synonyms = '';
        }

        // импортер
        if(isset($this->xml_data->importer) && trim($this->xml_data->importer) == "Да") {
            $importer = 1;
        } else {
            $importer = 0;
        }

        // регулируемый
        if(isset($this->xml_data->adjustable) && trim($this->xml_data->adjustable) == "Да") {
            $adjustable = 1;
        } else {
            $adjustable = 0;
        }

        $data = array(
            /* Новые поля */
            '1c_parent_id' => $this->xml_data->parent_id->count() == 0 ? null : trim($this->xml_data->parent_id),
            'is_component' => intval($this->xml_data->is_component[0]),
            'in_archive' => intval(trim($this->xml_data->in_archive)),
            'in_price' => intval(trim($this->xml_data->in_priсe)), // влияет только на архивные
            '1c_id' => intval(trim($this->xml_data->id_1c)),
            '1c_category_id' => intval(trim($this->xml_data->category_id_1c)),
            'name' => trim($this->xml_data->name),
            'default_sort' => trim($this->xml_data->default_sort),
            'description' => trim($this->xml_data->description),
            'code' => intval(trim($this->xml_data->code)),
            'content' => trim($this->xml_data->content),
            'more_about' => trim($this->xml_data->more_about),
            'vendor_code' => trim($this->xml_data->vendor_code),
            'barcode' => trim($this->xml_data->barcode),
            'codeTNVD' => trim($this->xml_data->codeTNVD),
            'country' => trim($this->xml_data->country),
            'apply' => trim($this->xml_data->apply),
            'shelf_life' => trim($this->xml_data->shelf_life),
            'guarantee_period' => intval($this->xml_data->guarantee_period),
            'equipment' => trim($this->xml_data->equipment),
            'mini_text' => trim($this->xml_data->mini_text),
            'weight' => trim($this->xml_data->weight),
            'width' => trim($this->xml_data->width),
            'depth' => trim($this->xml_data->depth),
            'height' => trim($this->xml_data->height),
            'packaging' => intval(trim($this->xml_data->packaging)),
            'youtube' => trim($this->xml_data->video),
            'brand' => intval($this->xml_data->brand),
            'factory' => trim($this->xml_data->factory),
            'is_new_item' => intval(trim($this->xml_data->is_new_item)),
            'date_new_item' => trim($date_new_item),
            'is_action' => intval(trim($this->xml_data->is_action)),
            'discounted' => $discounted,
            'date_open_action' => $date_open_action,
            'as_gift' => trim($this->xml_data->as_gift),
            'buy_with' => trim($this->xml_data->buy_with),
            'forget_buy' => trim($this->xml_data->forget_buy),
            'cheap_goods' => trim($this->xml_data->cheap_goods),
            'count' => intval(trim($this->xml_data->count)),
            'count_type' => intval(trim($this->xml_data->count_type)),
            'count_text' => trim($this->xml_data->count_text),
            'price_usd' => trim($this->xml_data->price_usd),
            'price_usd_1' => trim($this->xml_data->price_usd),
            'price_usd_2' => trim($this->xml_data->price_usd_2),
            'price_mr_usd' => trim($this->xml_data->price_mr_usd),
            'price_min_usd' => trim($this->xml_data->price_min_usd),
            'date_change_price' => $date_change_price,
            'popular' => trim($this->xml_data->popular),
            'certificate' => trim($this->xml_data->certificate->cert_name),
            'certificate_exp' => $sert_end_day,
            'certificate_file' => trim($this->xml_data->certificate->cert_file),
            'guide_file' => trim($this->xml_data->manual),
            'synonyms' => $synonyms,
            'importer' => $importer,
            'adjustable' => $adjustable,
        );

        if (!$data['discounted']) {
            $data['discounted'] = 0;
            $data['discounted_rub'] = 0;
        } else {
            $data['discounted_rub'] = '';
            $listDiscount = [];
            $prevlistDiscounts = explode(';', $data['discounted']);
            foreach ($prevlistDiscounts as $pLD) {
                list($count, $price) = explode('-', $pLD);
                $listDiscount[] = [
                    'count' => $count,
                    'price' => round(setting('header_usd') * $price, 2)
                ];
                $data['discounted_rub'] .= $count . '-' . round(setting('header_usd') * $price, 2) . ';';
            }

        }
        if (!$data['is_action']) {
            $data['is_action'] = 0;
        }

        $data['price_bel'] = ceil(setting('header_usd') * $data['price_usd'] * 100) / 100;
        $data['price_bel_1'] = ceil(setting('header_usd') * $data['price_usd_1'] * 100) / 100;
        $data['price_bel_2'] = ceil(setting('header_usd') * $data['price_usd_2'] * 100) / 100;

        $data['price_min_bel'] = ceil(setting('header_usd') * $data['price_min_usd'] * 100) / 100;

        $real_mr_bel = setting('header_usd_mrc') * $data['price_mr_usd']; // мрц без округления
        if($real_mr_bel > 100) {
            $round_mr_bel = ceil($real_mr_bel); // округляем до целого в большую сторону
        } elseif($real_mr_bel > 50) {
            $round_mr_bel = ceil($real_mr_bel * 10) / 10; // округляем до 1 знака после запятой в большую сторону
        } else {
            $round_mr_bel = round($real_mr_bel, 2);  // округляем до 2 знака после запятой математически
        }
        // записываем в дату
        $data['price_mr_bel'] = $round_mr_bel;

        $uid = trim($this->xml_data->id_1c);


        // сделать проверку статуса и менять..
        if (!$this->has_error) {
            Item::updateOrCreate(['1c_id' => $uid], $data);


            // if ($this->xml_data->actions->count()) {
            //     ItemAction::where('item_1c_id', $uid)->delete();
            //     foreach ($this->xml_data->actions->action as $action) {
            //         $data_act = array(
            //             'item_1c_id' => trim($uid),
            //             'count_items' => trim($action->count),
            //             'discount' => trim($action->discount),
            //         );

            //         //TODO: Тут обсуждаемо может они будут ID передавать или если передают блок тогда и все акции а не одну на обновление
            //         ItemAction::create($data_act);
            //     }
            // }

            if ($this->xml_data->images->count()) {
                ItemImage::where('item_1c_id', $uid)->delete();
                foreach ($this->xml_data->images->image as $action) {
                    $data_im = array(
                        'item_1c_id' => trim($uid),
                        'path_image' => trim('item-images/' . $action->path),
                    );
                    ItemImage::create($data_im);
                }
            }

        }

        if ($this->xml_data->characteristics->count()) {

            // Удаление характеристик
            \DB::table('characteristic_item')->where(['item_1c_id' => $uid])->delete();
            foreach ($this->xml_data->characteristics->characteristic as $characteristic) {

                $data_ch = array(
                    'characteristic_1c_id' => trim($characteristic->id_1c),
                    'item_1c_id' => trim($uid),
                    'value' => trim($characteristic->value),
                );

                $condition = [
                    'characteristic_1c_id' => trim($characteristic->id_1c),
                    'item_1c_id' => trim($uid)
                ];

                if (\DB::table('characteristic_item')->where($condition)->count()) {
                    \DB::table('characteristic_item')->where($condition)->update($data_ch);
                } else {
                    \DB::table('characteristic_item')->insert($data_ch);

                }

            }
        }

        if (!$this->has_error) {

            // $is_component = (array)$this->xml_data->is_component;
            // $item_id = intval(trim($this->xml_data->id_1c));
            // $schemes = isset($this->xml_data->schemes) ? $this->xml_data->schemes : []; // Получаем схемы
            // $schemes = isset($schemes->scheme) ? $schemes->scheme : []; // Получаем схемы
            // $parents = isset($this->xml_data->parents) ? $this->xml_data->parents : []; // Получаем родителей
            // $parents = isset($parents->parent) ? $parents->parent : []; // Получаем родителей

            if ($data['is_component'] == 0) //для товара
            {
                if($this->xml_data->schemes->count()) {
                    $scheme_ids = Scheme::where('item_id', $data['1c_id'])->get()->pluck('scheme_id')->toArray(); // Идентификатор схем, которые нужно удалить
                    $scheme_xml_ids = []; // Идентификатор схем, которые нужно удалить

                    foreach ($this->xml_data->schemes->scheme as $scheme) {

                        $scheme_xml_ids[] = (int)$scheme->scheme_id;

                        Scheme::UpdateOrCreate(
                            [
                                'item_id' => $data['1c_id'],
                                'scheme_id' => (int)$scheme->scheme_id,
                            ],
                            [
                                'scheme_image' => $scheme->scheme_file,
                                'scheme_no' => (int)$scheme->scheme_no,
                                'scheme_id' => (int)$scheme->scheme_id,
                                'scheme_name' => $scheme->scheme_name,
                                'item_id' => $data['1c_id'],
                            ]
                        );
                    }

                    Scheme::whereIn('scheme_id', array_diff($scheme_ids, $scheme_xml_ids))->delete();
                }

                // если товар ранее был запчастью, удаляем из схем
                $zp = SchemeParts::where('spare_id', $data['1c_id']);
                if($zp->get()->count()) {
                    $zp->delete();
                }
            }

            if ($data['is_component'] == 1) // для детали
            {

                if (isset($this->xml_data->parents)) {
                    if (!$this->xml_data->parents->parent->count()) {
                        $this->xml->addChild('success', "Undefined parents {$this->xml_data->id_1c}!");
                        $response = new Response($this->xml->asXML(), 200);
                        return $response;
                    }
                }

                try {
                    SchemeParts::where('spare_id', $data['1c_id'])->delete();

                    foreach ($this->xml_data->parents->parent as $parent) {
                        SchemeParts::create([
                            'scheme_no' => (int)$parent->scheme_no,
                            'scheme_id' => (int)$parent->scheme_id,
                            'number_in_schema' => $parent->number_on_scheme,
                            'spare_id' => $data['1c_id'],
                            'parent_id' => (int)$parent->parent_id,
                        ]);
                    }
                } catch (\Exception $e) {
                    $this->xml->addChild('success', "Undefined parent|scheme_no|spare_id {$this->xml_data->id_1c}!");
                    $response = new Response($this->xml->asXML(), 200);
                    return $response;
                }

            }

            $this->xml->addChild('success', "Record with item {$uid} successfully {$this->type_action}!");
        }

        $response = new Response($this->xml->asXML(), 200);
        return $response;
    }

    /**
     * @description Обновление или создания товара
     * @param XMLHelper $XMLHelper
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function postCreateOrUpdatePartial(XMLHelper $XMLHelper, Request $request)
    {

        // Поля которые должны прийти в XML
        $requared_fields = [
            'code', 'count', 'count_type'
        ];

        $this->xml_data = $XMLHelper->get_xml($request);

        if ($response = $this->xml_auth($this->xml_data, $this->xml)) {
            return $response;
        };


        $this->valid_empty($requared_fields);

        $data = array(

            'code' => trim($this->xml_data->code),
            'count' => trim($this->xml_data->count),
            'count_type' => trim($this->xml_data->count_type),
            'count_text' => trim($this->xml_data->count_text),

        );



        $uid = trim($this->xml_data->code);

        if (!$this->has_error) {
            Item::updateOrCreate(['1c_id' => $uid], $data);

        }
        if (!$this->has_error) {
            $this->xml->addChild('success', "Record with item {$uid} successfully {$this->type_action}!");
        }
        $response = new Response($this->xml->asXML(), 200);
        return $response;
    }


    /**
     * @param XMLHelper $XMLHelper
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDelete(XMLHelper $XMLHelper, Request $request)
    {
        $data['title'] = 'Удаление товара, с акциями и характеристиками';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/items/delete';

        $data['desc'] = 'Чтобы удалить товар с акциями, и характеристиками, нужно отправить запрос xml запрос который должен обязательно содержать поле:<br>
        <b>1с_ID товара</b> как на примере ниже<br/>';


        $this->xml->addChild('login', $this->login);
        $this->xml->addChild('password', $this->password);
        $this->xml->addChild('id_1c', '44114');

        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);

    }

    /**
     * @description Удаление товаров
     * @param XMLHelper $XMLHelper
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function postDelete(XMLHelper $XMLHelper, Request $request)
    {

        // Поля которые должны прийти в XML
        $requared_fields = ['id_1c'];

        // Получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        //Аутентификация
        if ($response = $this->xml_auth($this->xml_data, $this->xml)) {
            return $response;
        };

        // Проверка на пустоту
        $this->valid_empty($requared_fields);

        // Должно соответствовать названию полей в базе

        $id_1c = trim($this->xml_data->id_1c);


        if (Item::where('1c_id', $id_1c)->count() == 1) {
            Item::where('1c_id', $id_1c)->delete();
            ItemAction::where('item_1c_id', $id_1c)->delete();
            ItemImage::where('item_1c_id', $id_1c)->delete();
            CharacteristicItem::where('item_1c_id', $id_1c)->delete();
            // Добавить удаление изображений
        } else {
            $this->xml->addChild('error', "Record with item {$id_1c} not exists!");
            $this->has_error = true;
        }

        if (!$this->has_error) {
            $this->xml->addChild('success', "Record with item {$id_1c} with actions and images successfully deleted!");
        }

        $response = new Response($this->xml->asXML(), 200);

        return $response;

    }


    public function getTruncate(XMLHelper $XMLHelper, Request $request)
    {

        $data['title'] = 'Очистка таблицы с товарами';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/items/truncate';

        $data['desc'] = 'Чтобы очистить таблицу нужно отправить запрос xml запрос который должен обязательно содержать поле:<br>
        <b>Удалить все </b> как на примере ниже<br/>';

        $this->xml->addChild('login', $this->login);
        $this->xml->addChild('password', $this->password);
        $this->xml->addChild('delete', 'all');

        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);

    }

    /**
     * @description Очистка таблицы
     * @param XMLHelper $XMLHelper
     * @param Request $request
     * @return Response
     */
    public function postTruncate(XMLHelper $XMLHelper, Request $request)
    {

        // Получаем xml данные

        $this->xml_data = $XMLHelper->get_xml($request);

        //Аутентификация
        if ($response = $this->xml_auth($this->xml_data, $this->xml)) {
            return $response;
        };

        if ($this->xml_data->delete == 'all') {
            \DB::table('items')->truncate();
            $this->xml->addChild('success', "Table is clear!");
        }

        $response = new Response($this->xml->asXML(), 200);

        return $response;

    }


    // ДОбавить метод который будет изменять цену.
    public function getUpdatePrice(XMLHelper $XMLHelper, Request $request)
    {
        //dd($this->xml);

        $data['title'] = 'Обновление цены';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/items/updatePrice';

        $data['desc'] = '<p>Описание полей:<br>
        <ul>
            <li><b>id_1c</b> - ID категории из 1С. <b>INT, Обязательное</b></li>
            <li><b>price_usd</b> - Цена товара в USD <b>FLOAT, Обязательное</b></li>
            <li><b>price_usd_2</b> - Цена товара в USD <b>FLOAT, Обязательное</b></li>
            <li><b>price_mr_usd</b> - Цена минимальной раализации в USD. <b>FLOAT, Обязательное</b></li>
            <li><b>price_min_usd</b> - Минимальная цена товара <b>FLOAT, Обязательное</b></li>
        </ul>
        </p>';


        $this->xml->addChild('login', $this->login);
        $this->xml->addChild('password', $this->password);

        $this->xml->addChild('id_1c', '44511');

        $this->xml->addChild('price_usd', '175');
        $this->xml->addChild('price_usd_2', '500');
        $this->xml->addChild('price_mr_usd', '250');
        $this->xml->addChild('price_min_usd', '270');

        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);

    }

    /**
     * @description Изменение ТОЛЬКО цены у товара
     * @param XMLHelper $XMLHelper
     * @param Request $request
     * @return Response
     */
    public function postUpdatePrice(XMLHelper $XMLHelper, Request $request)
    {
        // Поля которые должны прийти в XML
        $requared_fields = ['id_1c',
            'price_usd', 'price_usd_2', 'price_mr_usd', 'price_min_usd'
        ];

        $this->xml_data = $XMLHelper->get_xml($request);
        if ($response = $this->xml_auth($this->xml_data, $this->xml)) {
            return $response;
        };

        $this->valid_empty($requared_fields);

        $data = array(

            '1c_id' => trim($this->xml_data->id_1c),
            'price_usd' => trim($this->xml_data->price_usd),
            'price_usd_1' => trim($this->xml_data->price_usd),
            'price_usd_2' => trim($this->xml_data->price_usd_2),
            'price_mr_usd' => trim($this->xml_data->price_mr_usd),
            'price_min_usd' => trim($this->xml_data->price_min_usd),
        );

        $data['price_bel'] = ceil(setting('header_usd') * $data['price_usd'] * 100) / 100;
        $data['price_bel_1'] = ceil(setting('header_usd') * $data['price_usd_1'] * 100) / 100;
        $data['price_bel_2'] = ceil(setting('header_usd') * $data['price_usd_2'] * 100) / 100;

        $data['price_min_bel'] = ceil(setting('header_usd') * $data['price_min_usd'] * 100) / 100;

        // если мрц больше 50р, округляем до 1 знака после запятой
        $real_mr_bel = setting('header_usd_mrc') * $data['price_mr_usd']; // мрц без округления
        if($real_mr_bel > 100) {
            $round_mr_bel = ceil($real_mr_bel); // округляем до целого в большую сторону
        } elseif($real_mr_bel > 50) {
            $round_mr_bel = ceil($real_mr_bel * 10) / 10; // округляем до 1 знака после запятой в большую сторону
        } else {
            $round_mr_bel = round($real_mr_bel, 2);  // округляем до 2 знака после запятой математически
        }
        // записываем в дату
        $data['price_mr_bel'] = $round_mr_bel;

        $uid = trim($this->xml_data->id_1c);

        if (!$this->has_error) {
            Item::updateOrCreate(['1c_id' => $uid], $data);
        }

        if (!$this->has_error) {
            $this->xml->addChild('success', "Record with item {$uid} successfully {$this->type_action}!");
        }

        $response = new Response($this->xml->asXML(), 200);
        return $response;
    }


    // Сделать возможность добавлять профиль, если его нету и сделать отправку пароля на почту
    // Контроллер по обновлению цены в баксах.
    // Контроллер по отдаче товара из корзины


    /**
     * @description Проверка полей на пустоту.
     * @param $req_fields
     */
    function valid_empty($req_fields)
    {
        foreach ($req_fields as $f) {
            if (trim($this->xml_data->{$f}) == '') {
                $this->xml->addChild('error', "Field {$f} cannot be empty!");
                $this->has_error = true;
            }
        }
    }


}
