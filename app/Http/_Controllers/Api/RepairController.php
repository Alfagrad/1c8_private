<?php

namespace App\Http\Controllers\Api;

use App\Item;
use App\ItemAction;
use App\ItemImage;
use App\Profile;
use App\ProfileAddress;
use App\ProfileContact;
use App\ProfileRepair;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Helpers\XMLHelper;
use Illuminate\Http\Response;

class RepairController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    public function getCreateOrUpdate(XMLHelper $XMLHelper)
    {
        //dd($this->xml);

        $data['title'] = 'Добавление и обновление ремонта у пользователя';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/repairs/createOrUpdate';

        $data['desc'] = '<p>Описание полей:<br>
        <ul>
            <li><b>1c_id</b> - Номер квитанции ремонта в 1с. <b>STRING, Обязательное</b></li>
            <li><b>user_unp</b> - УНП компании. <b>STRING</b></li>
            <li><b>date</b> - Дата поступления. <b>Обязательное</b></li>
            <li><b>shipment_date</b> - Дата отправки в ПВ.</li>
            <li><b>check_date</b> - Дата выдачи.</li>
            <li><b>return</b> - Возврат. 0-Не установлен,  1-Возврат, 2-Замена.</li>
            <li><b>nopay</b> - Вид ремонта. 0-платный, 1-бесплатный.</li>
            <li><b>doc_sum</b> - Стоимость ремонта.</li>
            <li><b>paid</b> - Признак оплаты. 0-не оплачен, 1-оплачен.</li>
            <li><b>repair_sum</b> - Стоимость ремонта.</li>
            <li><b>name</b> - Наименование продукта. <b>STRING, Обязательное</b></li>
            <li><b>serial</b> - Серийный номер.</li>
            <li><b>state</b> - Может быть три типа: 1 - В работе, 2 - Готово, 3 - Выдан. <b>INT, Обязательное</b></li>
            <li><b>comment</b> - Комментарий.</li>
            <li><b>works</b> - Блок с выполненными работами.
                <ul>
                   <li><b>work</b> - Работа 1</li>
                   <li><b>work</b> - Работа 2</li>
                   <li><b>work</b> - Работа 3</li>
                </ul>
            </li>
        </ul>
        </p>';


        $this->xml->addChild('login', $this->login);
        $this->xml->addChild('password', $this->password);
        $this->xml->addChild('id_1c', '99999');
        $this->xml->addChild('serial', '00585174852544');
        $this->xml->addChild('user_unp', '111222333');
        $this->xml->addChild('date', '5/30/2022');
        $this->xml->addChild('shipment_date', '6/2/2022');
        $this->xml->addChild('check_date', '6/3/2022');
        $this->xml->addChild('return', '0');
        $this->xml->addChild('nopay', '0');
        $this->xml->addChild('doc_sum', '160.50');
        $this->xml->addChild('paid', '0');
        $this->xml->addChild('name', 'Бетономешалка');
        $this->xml->addChild('state', '2');
        $this->xml->addChild('comment', 'Замена подшипника');
        $works = $this->xml->addChild('works');
        $works->addChild('work', 'Работа 1');
        $works->addChild('work', 'Работа 2');
        $works->addChild('work', 'Работа 3');

        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);
    }

    public function postCreateOrUpdate(XMLHelper $XMLHelper, Request $request){
        // Поля которые должны прийти в XML
        $requared_fields = [ 'id_1c', 'date', 'name', 'state'];

        $this->xml_data = $XMLHelper->get_xml($request);
        if ($response = $this->xml_auth($this->xml_data, $this->xml)) {
            return $response;
        };

        $this->valid_empty($requared_fields);

        // берем УНП
        $user_unp = trim($this->xml_data->user_unp);

        // id квитанции
        $id_1c = intval($this->xml_data->id_1c);

        // берем дату квитанции
        $receipt_date_arr = explode('/', trim($this->xml_data->date));
        $receipt_date = $receipt_date_arr[2]."-".$receipt_date_arr[0]."-".$receipt_date_arr[1];

        // берем дату готовности
        if(isset($this->xml_data->shipment_date) && trim($this->xml_data->shipment_date)) {
            $shipment_date_arr = explode('/', trim($this->xml_data->shipment_date));
            $shipment_date = $shipment_date_arr[2]."-".$shipment_date_arr[0]."-".$shipment_date_arr[1];
        } else {
            $shipment_date = "0000-00-00";
        }

        // берем дату выдачи
        if(isset($this->xml_data->check_date) && trim($this->xml_data->check_date)) {
            $check_date_arr = explode('/', trim($this->xml_data->check_date));
            $check_date = $check_date_arr[2]."-".$check_date_arr[0]."-".$check_date_arr[1];
        } else {
            $check_date = "0000-00-00";
        }

        // проверяем наличие поля и берем серийный номер
        if(isset($this->xml_data->serial) && trim($this->xml_data->serial)) {
            $serial = trim($this->xml_data->serial);
        } else {
            $serial = '';
        }

        // проверяем наличие поля works и содержимого
        if(isset($this->xml_data->works) && $this->xml_data->works->work->count()) {
            $works = '';
            // дописываем работы с переносом строки
            foreach($this->xml_data->works->work as $work) {
                $works .= $work."\n";
            }

            $works = trim($works);

        } else {
            $works = '';
        }

        // удаляем ремонты
        ProfileRepair::where('1c_id', $id_1c)->delete();

        // берем id профайлов
        $profiles = Profile::where('unp', $user_unp)->get(['id']);

        // если профайлы найдены
        if($profiles->count()) {

            // записываем ремонты
            foreach ($profiles as $profile) {

                $repair = new ProfileRepair;

                $repair->{'1c_id'} = $id_1c;
                $repair->serial = $serial;
                $repair->receipt_date = $receipt_date;
                $repair->check_date = $check_date;
                $repair->shipment_date = $shipment_date;
                $repair->return_item = intval($this->xml_data->return);
                $repair->repair_type = intval($this->xml_data->nopay);
                $repair->repair_sum = doubleval($this->xml_data->doc_sum);
                $repair->paid = intval($this->xml_data->paid);
                $repair->name = trim($this->xml_data->name);
                $repair->state = trim($this->xml_data->state);
                $repair->comment = isset($this->xml_data->comment) ? trim($this->xml_data->comment) : '';
                $repair->works = $works;

                $repair->profile_id = $profile->id;

                $res = $repair->save();

            }
        } else { // если нет

                $repair = new ProfileRepair;

                $repair->{'1c_id'} = $id_1c;
                $repair->serial = $serial;
                $repair->receipt_date = $receipt_date;
                $repair->check_date = $check_date;
                $repair->shipment_date = $shipment_date;
                $repair->return_item = intval($this->xml_data->return);
                $repair->repair_type = intval($this->xml_data->nopay);
                $repair->repair_sum = doubleval($this->xml_data->doc_sum);
                $repair->paid = intval($this->xml_data->paid);
                $repair->name = trim($this->xml_data->name);
                $repair->state = trim($this->xml_data->state);
                $repair->comment = isset($this->xml_data->comment) ? trim($this->xml_data->comment) : '';
                $repair->works = $works;

                $repair->profile_id = 0;

                $res = $repair->save();
        }

        if (!$this->has_error) {
            $this->xml->addChild('success', "Record with repair item {$id_1c} successfully!");
        }

        $response = new Response($this->xml->asXML(), 200);
        return $response;
    }

    public function getDelete(XMLHelper $XMLHelper)
    {
        $data['title'] = 'Удаление товара, с акциями и характеристиками';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/repairs/delete';

        $data['desc'] = 'Чтобы удалить ремонт, нужно отправить запрос xml запрос который должен обязательно содержать поле:<br>
        <b>1C ID товара</b> как на примере ниже<br/>';


        $this->xml->addChild('login', $this->login);
        $this->xml->addChild('password', $this->password);
        $this->xml->addChild('id_1c', '44114');

        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);

    }


    public function postDelete(XMLHelper $XMLHelper, Request $request){

        // Поля которые должны прийти в XML
        $requared_fields = ['id_1c'];

        // Получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);
        //Аутентификация
        if( $response = $this->xml_auth($this->xml_data, $this->xml) ){
            return $response;
        };

        // Проверка на пустоту
        $this->valid_empty($requared_fields);

        // Должно соответствовать названию полей в базе

        $id_1c = trim($this->xml_data->id_1c);


        if( ProfileRepair::where('1c_id', $id_1c)->count()){
            ProfileRepair::where('1c_id', $id_1c)->delete();
        } else {
            $this->xml->addChild('error', "Record with item {$id_1c} not exists!");
            $this->has_error = true;
        }

        if(!$this->has_error){
            $this->xml->addChild('success', "Record with item {$id_1c} with actions and images successfully deleted!");
        }

        $response = new Response($this->xml->asXML(), 200);

        return $response;

    }


    public function getTruncate(XMLHelper $XMLHelper)
    {

        $data['title'] = 'Очистка таблицы с товарами';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/repairs/truncate';

        $data['desc'] = 'Чтобы очистить таблицу нужно отправить запрос xml запрос который должен обязательно содержать поле:<br>
        <b>Удалить все </b> как на примере ниже<br/>';

        $this->xml->addChild('login', $this->login);
        $this->xml->addChild('password', $this->password);
        $this->xml->addChild('delete', 'all');

        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);

    }


    public function postTruncate(XMLHelper $XMLHelper){

        // Получаем xml данные
        $this->xml_data = $this->get_xml();

        //Аутентификация
        if( $response = $this->xml_auth($this->xml_data, $this->xml) ){
            return $response;
        };

        if($this->xml_data->delete == 'all' ){
            \DB::table('profile_repairs')->truncate();
            $this->xml->addChild('success', "Table is clear!");
        }

        $response = new Response($this->xml->asXML(), 200);

        return $response;

    }
    
    
    
    

    function valid_empty($req_fields){
        foreach($req_fields as $f){
            if(trim($this->xml_data->{$f}) == ''){
                $this->xml->addChild('error', "Field {$f} cannot be empty!");
                $this->has_error = true;
            }
        }
    }








}
