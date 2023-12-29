<?php

namespace App\Http\Controllers\Api;

use App\Item;
use App\ItemAction;
use App\ItemImage;
use App\Order;
use App\Profile;
use App\ProfileAddress;
use App\ProfileContact;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Helpers\XMLHelper;
use Illuminate\Http\Response;

class OrderController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    public function getCreateOrUpdate(XMLHelper $XMLHelper)
    {
        //dd($this->xml);

        $data['title'] = 'Добавление и обновление пользователя';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/users/createOrUpdate';

        $data['desc'] = '<p>Описание полей:<br>
        <ul>
            <li><b>name</b> - Имя <b>STRING, Обязательное</b></li>
            <li><b>email</b> - Email <b>STRING, Обязательное</b></li>
            
            <li><b>company_name</b> - Название компании. <b>STRING, Обязательное</b></li>
            <li><b>company_address</b> - Адрес компании. <b>STRING, Обязательное</b></li>
            
            <li><b>unp</b> - УНП. <b>STRING, Обязательное</b></li>
            
            <li><b>bank_account</b> - Расчетный счет (IBAN). <b>STRING, Обязательное</b></li>
            <li><b>bank_name</b> - БИК Банка. <b>STRING, Обязательное</b></li>
            
            <li><b>trade_object</b> - Торговые объекты. <b>STRING, Обязательное</b></li>
            <li><b>shops</b> - Магазины <b>STRING, Обязательное</b></li>
            <li><b>coverage_area</b> - Зона покрытия <b>STRING, Обязательное</b></li>
           
            <li><b>type_price</b> - Тип цены, может быть 1 или 2. <b>INT, Обязательное</b></li>
           
            <li><b>manager_name</b> -  Имя менеджера<b>STRING</b></li>
            <li><b>manager_email</b> - email менеджера<b>STRING</b></li>
            <li><b>manager_viber</b> - viber менеджера <b>STRING</b></li>
            <li><b>manager_skype</b> - skype менеджера <b>STRING</b></li>
           
            <li><b>subscribes</b>Рассылка<b></b>
                <ul>
                   <li><b>xls_weekly</b> - Прайс каждую неделю, 0 или 1 <b>INT, Обязательное</b></li>
                   <li><b>news</b> - Рассылка новостей, 0 или 1. <b>INT, Обязательное</b></li>
                   <li><b>new_items</b> - Поступление товаров, 0 или 1. <b>INT, Обязательное</b></li>
                   <li><b>copy_order</b> - Копия заказов, 0 или 1. <b>INT, Обязательное</b></li>
                   <li><b>change_price</b> - Изменение цены, 0 или 1. <b>INT, Обязательное</b></li>
                </ul>
            </li>
           
            <li><b>contacts</b> -  Контакты<b></b></li>
            <li><b>contact</b> Блок с контактами<b></b>
                <ul>
                   <li><b>name</b> - Имя. <b>STRING, Обязательное</b></li>
                   <li><b>phone</b> - Телефон. <b>STRING, Обязательное</b></li>
                </ul>
            </li>
            
            <li><b>addresses</b> -  Адреса компании<b></b></li>
            <li><b>address</b> Блок с адресом<b></b>
                <ul>
                   <li><b>address</b> - Адрес. <b>STRING, Обязательное</b></li>
                   <li><b>comment</b> - Комментарий. <b>STRING, Обязательное, или пустая строка</b></li>
                </ul>
            </li>
            
        </ul>
        </p>';


        $this->xml->addChild('login', $this->login);
        $this->xml->addChild('password', $this->password);

        $this->xml->addChild('name', 'Ivan');
        $this->xml->addChild('email', 'test@test.com');

        $this->xml->addChild('company_name', 'Тестовая компания - 1');
        $this->xml->addChild('company_address', 'г. Минск, ул. Независимости 1, д.1');
        $this->xml->addChild('unp', '123456789');
        $this->xml->addChild('bank_account', '1251254521455255454454');
        $this->xml->addChild('bank_name', '12352456245524451');

        $this->xml->addChild('trade_object', 'Газеты, пароходы');
        $this->xml->addChild('shops', 'Магазины на Октябньской и Немиге');
        $this->xml->addChild('coverage_area', 'Минск');

        $this->xml->addChild('type_price', 1);

        $this->xml->addChild('manager_name', 'Алексей');
        $this->xml->addChild('manager_email', 'manager@alfastock.by');
        $this->xml->addChild('manager_viber', '+375258996255');
        $this->xml->addChild('manager_skype', 'alfa_manager');


        $subscribes = $this->xml->addChild('subscribes');

        $subscribes->addChild('xls_weekly', 1);
        $subscribes->addChild('news', 1);
        $subscribes->addChild('new_items', 1);
        $subscribes->addChild('copy_order', 1);
        $subscribes->addChild('change_price', 1);

        
        $contacts = $this->xml->addChild('contacts');
        $contact  = $contacts->addChild('contact');
        $contact->addChild('name', 'Марина Менеджер');
        $contact->addChild('phone', '375(25)980-22-22');

        $contact = $contacts->addChild('contact');
        $contact->addChild('name', 'Иван Директор');
        $contact->addChild('phone', '375(25)980-11-22');


        $addresses = $this->xml->addChild('addresses');
        $address  = $addresses->addChild('address');
        $address->addChild('address', 'Минск, ул. Горецкого 5');
        $address->addChild('comment', 'Работает с 10 до 18');

        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);

    }

    public function postCreateOrUpdate(XMLHelper $XMLHelper, Request $request){
        // Поля которые должны прийти в XML
        $requared_fields = [ 'name', 'email', 'company_name', 'company_address',
            'unp', 'bank_account', 'bank_name', 'type_price' ];

        $this->xml_data = $XMLHelper->get_xml($request);
        if ($response = $this->xml_auth($this->xml_data, $this->xml)) {
            return $response;
        };

        $this->valid_empty($requared_fields);

        $data = array(
            'name' => trim($this->xml_data->name),
            'email' => trim($this->xml_data->email),

            'company_name' => trim($this->xml_data->company_name),
            'company_address' => trim($this->xml_data->company_address),
            'unp' => trim($this->xml_data->unp),
            'bank_account' => trim($this->xml_data->bank_account),
            'bank_name' => trim($this->xml_data->bank_name),

            'trade_object' => trim($this->xml_data->trade_object),
            'shops' => trim($this->xml_data->shops),
            'coverage_area' => trim($this->xml_data->coverage_area),

            'type_price' => trim($this->xml_data->type_price),

            'manager_name' => trim($this->xml_data->manager_name),
            'manager_email' => trim($this->xml_data->manager_email),
            'manager_viber' => trim($this->xml_data->manager_viber),
            'manager_skype' => trim($this->xml_data->manager_skype),


          );


        $uid = trim($this->xml_data->unp);

        if (!$this->has_error) {
            $profile = Profile::updateOrCreate( ['unp'=>$uid], $data);
           
            if($this->xml_data->addresses->count()){
                //$existAddresses = ProfileAddress::where('profile_id', $profile->id)->get();
                //$saveAddresses = [];
                ProfileAddress::where('profile_id', $profile->id)->delete();
                foreach ($this->xml_data->addresses->address as $address){
                    $data = array(
                        'profile_id' => $profile->id,
                        'address' => trim($address->address),
                        'comment' => trim($address->comment),
                    );


                    ProfileAddress::create($data);

                    /*
                    if(!$existAddresses){
                        ProfileAddress::create($data);
                        continue;
                    }

                    foreach ($existAddresses as $existAddr){
                        if($data['phone'] == $existAddr->phone and $data['address'] == $existAddr->address ){
                            $saveAddresses[] = $existAddr->id;
                        } else {
                            $profileAddress = ProfileAddress::create($data);
                            $saveAddresses[] = $profileAddress->id;
                        }
                    }
                    */
                }

               // ProfileAddress::where('profile_id', $profile->id)->whereNotIn('id',$saveAddresses)->update(['state' => 5]);
            }

            if($this->xml_data->contacts->count()){
                ProfileContact::where('profile_id', $profile->id)->delete();
                foreach ($this->xml_data->contacts->contact as $contact){
                    $data = array(
                        'profile_id' => $profile->id,
                        'name' => trim($contact->name),
                        'phone' => trim($contact->phone),
                        //'state' => 1
                    );
                    ProfileContact::create($data);

                }
            }
        }

        if (!$this->has_error) {
            $this->xml->addChild('success', "Record with item {$uid} successfully {$this->type_action}!");
        }


        $response = new Response($this->xml->asXML(), 200);
        return $response;
    }


    // Блокировать пользователя

    public function getBlock(XMLHelper $XMLHelper)
    {
        $data['title'] = 'Блокирование пользователя';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/users/blocked';

        $data['desc'] = 'Чтобы заблокировать пользователя  нужно отправить запрос xml запрос который должен обязательно содержать поле:<br>
        <b>УНП компании</b> как на примере ниже<br/>';


        $this->xml->addChild('login', $this->login);
        $this->xml->addChild('password', $this->password);
        $this->xml->addChild('unp', '44114');

        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);

    }

    public function postBlock(XMLHelper $XMLHelper, Request $request){

        // Поля которые должны прийти в XML
        $requared_fields = ['unp'];

        // Получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        //Аутентификация
        if( $response = $this->xml_auth($this->xml_data, $this->xml) ){
            return $response;
        };

        // Проверка на пустоту
        $this->valid_empty($requared_fields);

        // Должно соответствовать названию полей в базе

        $unp = trim($this->xml_data->unp);


        if( Profile::where('unp', $unp)->count() == 1 ){
            Profile::where('unp', $unp)->update(['is_blocked' => 1]);
        } else {
            $this->xml->addChild('error', "Record with user {$unp} not exists!");
            $this->has_error = true;
        }

        if(!$this->has_error){
            $this->xml->addChild('success', "Record with user {$unp} successfully blocked!");
        }

        $response = new Response($this->xml->asXML(), 200);

        return $response;

    }


    // Получение списка новых заказов.
    public function getNewOrder(XMLHelper $XMLHelper){

        $data['title'] = 'Получение новой заявки';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/orders/show/new';

        $data['desc'] = 'Чтобы получить новую заявку нужно отправить запрос xml запрос который должен обязательно содержать поле логин и пароль<br>
        <b>sucsessful</b> - номер последней успешной заявки<br>
        <b>replay</b> 0 - получить все после успешной, 1 - повторить последнюю передачу';

        $this->xml->addChild('login', $this->login);
        $this->xml->addChild('password', $this->password);
        $this->xml->addChild('sucsessful', 11111);
        $this->xml->addChild('replay', 0);


        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);

    }

    public function postNewOrder(XMLHelper $XMLHelper, Request $request){

        $requared_fields = [];
        $this->xml_data = $XMLHelper->get_xml($request);

        //Аутентификация
        if( $response = $this->xml_auth($this->xml_data, $this->xml) ){
            return $response;
        };

        // определяем последний удачный ордер
        $last_order = intval($this->xml_data->sucsessful);

        if(!$last_order) { // если пусто, отдаем ошибку
            return $this->xml->addChild('error', "Field - sucsessful - is empty!");
        } else {
            // собираем ордера после last_order, плюс is_send=0, поставленный вручную
            $records = Order::with('items', 'profileId')
                ->where('id', '>', $last_order)
                ->orWhere('is_send', 0)
                ->get();

            // если ничего нет
            if(!$records->count()) {
                return $this->xml->addChild('error', "New orders don't exists");
            } else {
                // формируем ответ
                $orders = $this->xml->addChild('orders');

                foreach ($records as $record){

                    $order = $orders->addChild('order');
                    $order->addChild('id', $record->id);
                    $order->addChild('name', $record->profileId->name);
                    $order->addChild('email', $record->profileId->email);
                    $order->addChild('company_name', $record->profileId->company_name);
                    $order->addChild('company_address', $record->profileId->company_address);
                    $order->addChild('unp', $record->profileId->unp);
                    $order->addChild('payment', $record->calculation);

                    if($record->delivery == 'Доставка'){
                        $order->addChild('delivery', 'СиламиПродавца');
                    } else {
                        $order->addChild('delivery','Самовывоз');
                    }


                    if($record->delivery == 'Доставка'){
                        $order->addChild('address', $record->address);
                    } else {
                        $order->addChild('address', 'Самовывоз');
                    }

                    $order->addChild('general_discount', $record->general_discount);
                    $order->addChild('general_price', $record->price);
                    $order->addChild('comment', $record->comment);

                    // тип цен: 2-розница (для наказанных), 0-дилер (остальные)
                    if($record->profileId->markup == 200) $type_price = 2;
                        else $type_price = 0;
                    $order->addChild('type_price', $type_price);

                    $order->addChild('is_service', $record->profileId->is_service);

                    // параметры Сервиса
                    if($record->profileId->is_service == 1) {
                        $order->addChild('client_name', $record->client_name.', '.$record->client_phone);
                        $order->addChild('item_name', $record->item_name);
                        $order->addChild('item_1c_id', $record->item_1c_id);
                        $order->addChild('item_sn', $record->item_sn);
                        $order->addChild('item_sale_date', $record->item_sale_date);
                        $order->addChild('item_defect', $record->item_defect);
                        $order->addChild('item_diagnostic', $record->item_diagnostic);
                    }

                    $contacts =$order->addChild('items');

                    foreach($record->items as $i){
                        if(isset($i->item->code)){
                            $contact  = $contacts->addChild('item');
                            $contact->addChild('code', $i->item->code);
                            $contact->addChild('count', $i->item_count);
                            $contact->addChild('general_price', $i->item_sum_price);

                        }
                    }
                }
            }
        }

        // берем id ордеров
        $order_id = $records->pluck('id')->toArray();

        // убираем статус повтора предыдущих
        Order::where([['id', '<=', $last_order], ['is_replay', 1]])->update(['is_replay' => 0]);
        // ставим статусы для новых
        Order::whereIn('id', $order_id)->update([
            'is_replay' => 1,
            'is_send' => 1,
        ]);

        $this->xml = $XMLHelper->beauty_xml($this->xml);

        \Log::info( Carbon::parse('now') . ' ' . $this->xml );
        $response = new Response($this->xml , 200);

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
