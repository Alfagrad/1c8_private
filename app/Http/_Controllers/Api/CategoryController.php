<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Characteristic;
use App\CharacteristicItem;
use App\Item;
use App\ItemAction;
use App\ItemImage;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Helpers\XMLHelper;
use Illuminate\Http\Response;

class CategoryController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    public function getCreateOrUpdate(XMLHelper $XMLHelper)
    {
        //dd($this->xml);

        $data['title'] = 'Добавление и обновление категории и характеристик';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/category/createOrUpdate';

        $data['desc'] = '<p>Описание полей:<br>
        <ul>
            <li><b>id_1c</b> - ID категории из 1С. <b>INT, Обязательное</b></li>
            <li><b>parent_id_1c</b> - Идентификатор родителя из 1С. <b>INT, если нет то 0</b></li>
            <li><b>name</b> - Название категории. <b>STRING, Обязательное</b></li>
            <li><b>image_path</b> - Название изображения</b> должно находиться в /home/alfastok/public_html/storage/categories</li>
            
			<li><b>characteristics</b> -  Привязанные характеристики категории<b></b></li>
            <li><b>default_sort</b> - Сортировка по умолчанию <b>STRING 100</b></li>
            <li><b>characteristic</b> Блок харатеристики <b></b>
                <ul>
                   <li><b>char_id_1c</b> - ID характеристики из 1С. <b>INT, Обязательное</b></li>
                   <li><b>char_name</b> - Название характеристики. <b>STRING, Обязательное</b></li>
                   <li><b>char_type</b> - Тип характиристики. <b>1 - text, 2 - bool, 3 - int, 4- float</b></li>
                   <li><b>char_unit</b> - Единицы измерения. <b>Не обязательное</b></li>
                   <li><b>is_search</b> - Участвует ли в поиске. <b>0 -  не участвует, 1 - участвует</b></li>
                  
                </ul>
            </li>
            
        </ul>
        </p>';


        $this->xml->addChild('login', 'AlfaStockApi');
        $this->xml->addChild('password', 'PC6wpCjZ');

        $this->xml->addChild('id_1c', '44511');
        $this->xml->addChild('parent_id_1c', '445');
        $this->xml->addChild('image_path', 'Gr9nfeTrBj8YS5BiM1ct.jpg');
        $this->xml->addChild('name', 'Кабель');

        $this->xml->addChild('default_sort', 1);

        $characteristics = $this->xml->addChild('characteristics');
        $characteristic = $characteristics->addChild('characteristic');
        $characteristic->addChild('id_1c', '1000000');
        $characteristic->addChild('name', 'Материал');
        $characteristic->addChild('type', '1'); // Тип данных - text, bool, int, float
        $characteristic->addChild('unit', ''); //
        $characteristic->addChild('is_search', '0'); //


        $characteristic = $characteristics->addChild('characteristic');
        $characteristic->addChild('id_1c', '1000001');
        $characteristic->addChild('name', 'Длина');
        $characteristic->addChild('type', '4'); // Тип данных - text, bool, int, float
        $characteristic->addChild('unit', 'метр'); //
        $characteristic->addChild('is_search', '1'); //


//        $rating = $this->xml->addChild('rating', '5');
//        $rating->addAttribute('type', 'stars');


        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);

    }

    public function postCreateOrUpdate(XMLHelper $XMLHelper, Request $request)
    {
        // Поля которые должны прийти в XML
        $requared_fields = ['id_1c', 'name'];

        // Получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        if ($response = $this->xml_auth($this->xml_data, $this->xml)) {
            return $response;
        };


        // Проверка на пустоту
        $this->valid_empty($requared_fields);


        // Должно соответствовать названию полей в базе
        $data = array(
            'name' => trim($this->xml_data->name),
            '1c_id' => trim($this->xml_data->id_1c),
            'parent_1c_id' => trim($this->xml_data->parent_id_1c),
            'default_sort' => trim($this->xml_data->default_sort),
            'slug' => str_slug(trim($this->xml_data->name), '-'),
        );

        if (trim($this->xml_data->image_path)) {
            $data['image_path'] = 'item-images/' . $this->xml_data->image_path;
        }


        $uid = trim($this->xml_data->id_1c);


        if (!$this->has_error) {
            Category::updateOrCreate(['1c_id' => $uid], $data);
        }


        if ($this->xml_data->characteristics->count()) {

            // Очищаем характеристики, для видимости актуальной информации если их пришло меньше чем сейчас есть в бд
            Characteristic::where('category_1c_id', $uid)->delete();

            foreach ($this->xml_data->characteristics->characteristic as $characteristic) {
                $data = array(
                    '1c_id' => trim($characteristic->id_1c),
                    'category_1c_id' => trim($uid),
                    'name' => trim($characteristic->name),
                    'type' => trim($characteristic->type),
                    'unit' => trim($characteristic->unit),
                    'is_search' => trim($characteristic->is_search),

                );
                $c_id = trim($characteristic->id_1c);

                Characteristic::updateOrCreate(['1c_id' => $c_id, 'category_1c_id' => $uid], $data);
            }
        }

        if (!$this->has_error) {
            $this->xml->addChild('success', "Record with article {$uid} successfully {$this->type_action}!");
        }

        $response = new Response($this->xml->asXML(), 200);

        return $response;
    }


    public function getDelete(XMLHelper $XMLHelper)
    {
        $data['title'] = 'Удаление категории с характеристиками';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/category/delete';

        $data['desc'] = 'Чтобы удалить категорию с характеристикой нужно отправить запрос xml запрос который должен обязательно содержать поле:<br>
        <b>1с ID категории </b> как на примере ниже<br/>';


        $this->xml->addChild('login', 'AlfaStockApi');
        $this->xml->addChild('password', 'PC6wpCjZ');
        $this->xml->addChild('id_1c', '44114');

        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);

    }


    public function postDelete(XMLHelper $XMLHelper, Request $request)
    {

        $requared_fields = ['id_1c'];
        $this->xml_data = $XMLHelper->get_xml($request);
        if ($response = $this->xml_auth($this->xml_data, $this->xml)) {
            return $response;
        };

        // Проверка на пустоту
        $this->valid_empty($requared_fields);

        // Должно соответствовать названию полей в базе
        $uid = trim($this->xml_data->id_1c);

        if (Category::where('1c_id', $uid)->count() == 1) {
            $category = Category::with('subCategory.subCategory')->where('1c_id', $uid)->first();
            foreach ($category->subCategory as $sc) {
                foreach ($sc->subCategory as $ssc) {
                    $this->deleteItems($ssc->items);
                    Characteristic::where('category_1c_id', $ssc->{'1c_id'})->delete();
                }
                $sc->subCategory()->delete();
                $this->deleteItems($sc->items);
                Characteristic::where('category_1c_id', $sc->{'1c_id'})->delete();
            }
            $category->subCategory()->delete();
            $this->deleteItems($category->items);
            $category->delete();
            Characteristic::where('category_1c_id', $uid)->delete();

        } else {
            $this->xml->addChild('error', "Record with category {$uid} not exists!");
            $this->has_error = true;
        }

        if (!$this->has_error) {
            $this->xml->addChild('success', "Record with category {$uid} with characteristics successfully deleted!");
        }

        $response = new Response($this->xml->asXML(), 200);
        return $response;
    }


    public function getTruncate(XMLHelper $XMLHelper)
    {

        $data['title'] = 'Очистка таблицы с категориями';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/category/truncate';

        $data['desc'] = 'Чтобы очистить таблицу нужно отправить запрос xml запрос который должен обязательно содержать поле:<br>
        <b>Удалить все </b> как на примере ниже<br/>';

        $this->xml->addChild('login', 'AlfaStockApi');
        $this->xml->addChild('password', 'PC6wpCjZ');
        $this->xml->addChild('delete', 'all');

        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);

    }


    public function postTruncate(XMLHelper $XMLHelper, Request $request)
    {

        // Получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        //Аутентификация
        if ($response = $this->xml_auth($this->xml_data, $this->xml)) {
            return $response;
        };

        if ($this->xml_data->delete == 'all') {
            \DB::table('categories')->truncate();
            \DB::table('characteristics')->truncate();
            \DB::table('characteristic_item')->truncate();
            \DB::table('items')->truncate();
            \DB::table('item_actions')->truncate();
            \DB::table('item_images')->truncate();

            $this->xml->addChild('success', "Table is clear!");
        }

        $response = new Response($this->xml->asXML(), 200);
        return $response;

    }


    function valid_empty($req_fields)
    {
        foreach ($req_fields as $f) {
            if (trim($this->xml_data->{$f}) == '') {
                $this->xml->addChild('error', "Field {$f} cannot be empty!");
                $this->has_error = true;
            }
        }
    }


    function deleteItems($items)
    {
        foreach ($items as $item) {
            $itemId = $item->{'1c_id'};
            if (Item::where('1c_id', $itemId)->count() == 1) {
                Item::where('1c_id', $itemId)->delete();
                ItemAction::where('item_1c_id', $itemId)->delete();
                ItemImage::where('item_1c_id', $itemId)->delete();
                CharacteristicItem::where('item_1c_id', $itemId)->delete();
            }
        }
    }


}
