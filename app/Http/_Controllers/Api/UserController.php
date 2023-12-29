<?php

namespace App\Http\Controllers\Api;

use App\Item;
use App\ItemAction;
use App\ItemImage;
use App\Profile;
use App\ProfileAddress;
use App\ProfileContact;
use App\ProfileSubscribe;
use App\User;
use App\BrandShop;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Helpers\XMLHelper;
use Illuminate\Http\Response;

class UserController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    public function getCreateOrUpdate(XMLHelper $XMLHelper)
    {
        //dd($this->xml);

        $data['title'] = 'Обновление компании';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/users/createOrUpdate';

        $data['desc'] = '<p>Описание полей:<br>
        <ul>
            <li><b>name</b> - Имя <b>STRING, Обязательное</b></li>
            <li><b>email</b> - Email <b>STRING, Обязательное</b></li>
            
            <li><b>company_name</b> - Название компании. <b>STRING, Обязательное</b></li>
            <li><b>company_address</b> - Адрес компании. <b>STRING, Обязательное</b></li>
            
            <li><b>unp</b> - УНП. <b>STRING, Обязательное</b></li>
            
            <li><b>bank_account</b> - Расчетный счет(IBAN). <b>STRING, Обязательное</b></li>
            <li><b>bank_bik</b> - БИК Банка <b>STRING, Обязательное</b></li>
            
            <li><b>trade_object</b> - Торговые объекты. <b>STRING, Обязательное</b></li>
            <li><b>shops</b> - Магазины <b>STRING, Обязательное</b></li>
            <li><b>coverage_area</b> - Зона покрытия <b>STRING, Обязательное</b></li>
           
            <li><b>type_price</b> - Тип цены, может быть 1 или 2. <b>INT, Обязательное</b></li>
            <li><b>discount</b> - Персональная скидка <b>INT, Обязательное</b></li>
            <li><b>discount_text</b> - Текст к персональной скидки<b>STRING</b></li>
           
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
        $this->xml->addChild('bank_bik', '12154141744');

        $this->xml->addChild('trade_object', 'Газеты, пароходы');
        $this->xml->addChild('shops', 'Магазины на Октябньской и Немиге');
        $this->xml->addChild('coverage_area', 'Минск');

        $this->xml->addChild('type_price', 1);
        $this->xml->addChild('discount', 0);
        $this->xml->addChild('discount_text', 'За своевременную оплату');

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
        $contact = $contacts->addChild('contact');
        $contact->addChild('name', 'Марина Менеджер');
        $contact->addChild('phone', '375(25)980-22-22');

        $contact = $contacts->addChild('contact');
        $contact->addChild('name', 'Иван Директор');
        $contact->addChild('phone', '375(25)980-11-22');


        $addresses = $this->xml->addChild('addresses');
        $address = $addresses->addChild('address');
        $address->addChild('address', 'Минск, ул. Горецкого 5');
        $address->addChild('comment', 'Работает с 10 до 18');

        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);

    }

    public function postCreateOrUpdate(XMLHelper $XMLHelper, Request $request)
    {
        // Поля которые должны прийти в XML
        $requared_fields = [
            'name',
            'email',
            'company_name',
            'company_address',
            'unp',
            'type_price'
        ];

        $this->xml_data = $XMLHelper->get_xml($request);
        if ($response = $this->xml_auth($this->xml_data, $this->xml)) {
            return $response;
        };


        $this->valid_empty($requared_fields);

        // определяем id главного контрагента
        $main_user = Profile::where('unp', $this->xml_data->unp)->first(['id']);
        if($main_user) {
            // получаем id
            $main_user_id = $main_user->id;
        } else {
            // или выдаем ошибку
            $this->xml->addChild('error', "Контрагент с УНП ".$this->xml_data->unp." не найден!");
            $this->has_error = true;
        }

        // если приходит type_price=2 (наказание за несоблюдение правил),
        // устанавливаем заведомо высокий markup, чтобы дилерская и розничная цена были равны
        $type_price = trim(intval($this->xml_data->type_price));
        $markup = trim(intval($this->xml_data->markup));
        if($type_price == 2) $markup = 200;

        if (!$this->has_error) {

            // собираем данные для главного контрагента
            $data = array(
                'name' => trim($this->xml_data->name),
                'email' => trim($this->xml_data->email),
                '1c_id' => trim($this->xml_data->id),
                'company_name' => trim($this->xml_data->company_name),
                'company_address' => trim($this->xml_data->company_address),
                'bank_account' => trim($this->xml_data->bank_account),
                'bank_name' => trim($this->xml_data->bank_bik),
                'trade_object' => trim($this->xml_data->trade_object),
                'shops' => trim($this->xml_data->shops),
                'coverage_area' => trim($this->xml_data->coverage_area),
                'type_price' => $type_price,
                'markup' => $markup,
                'discount' => trim($this->xml_data->discount),
                'discount_text' => trim($this->xml_data->discount_text),
                'manager_name' => trim($this->xml_data->manager_name),
                'manager_email' => trim($this->xml_data->manager_email),
                'manager_viber' => trim($this->xml_data->manager_viber),
                'manager_skype' => trim($this->xml_data->manager_skype),
            );

            // обновляем данные главного контрагента
            Profile::where('id', $main_user_id)->update($data);

            // обновляем / добавляем данные по торговым объектам *************************
            // для размещения на брендовых сайтах ****************************************

            // удаляем все торговые объекты контрагента
            BrandShop::where('profile_1c_id', $main_user_id)->delete();

            if ($this->xml_data->brands->brand->count()) {

                foreach($this->xml_data->brands->brand as $brand) {

                    if ($brand->brand_adresses->brand_adress->count()) {

                        foreach($brand->brand_adresses->brand_adress as $address) {

                            $brand_shop = new BrandShop;

                            $brand_shop->profile_1c_id = $main_user_id;
                            $brand_shop->city = trim($address->city);
                            $brand_shop->address = trim($address->adress);
                            $brand_shop->latitude = trim(floatval($address->latitude));
                            $brand_shop->longitude = trim(floatval($address->longitude));
                            $brand_shop->site_url = trim($address->site);
                            $brand_shop->contact = trim($address->telephone);
                            $brand_shop->logo_file = trim($address->logo);
                            $brand_shop->brand_1c_id = trim(intval($brand->brand_id_1c));

                            $brand_shop->save();

                        }

                    }

                }

            }
            // dd($this->xml_data->brands->count());









            // обновляем контакты главного контрагента
            if ($this->xml_data->contacts->count()) {

                // удаляем текущие
                ProfileContact::where('profile_id', $main_user_id)->delete();

                // собираем данные и добавляем заново
                foreach ($this->xml_data->contacts->contact as $contact) {
                    $data = array(
                        'profile_id' => $main_user_id,
                        'name' => trim($contact->name),
                        'phone' => trim($contact->phone),
                    );
                    ProfileContact::create($data);
                }
            }

            // обновляем адреса главного контрагента
            if ($this->xml_data->addresses->count()) {

                // удаляем текущие
                ProfileAddress::where('profile_id', $main_user_id)->delete();

                // собираем данные и добавляем заново
                foreach ($this->xml_data->addresses->address as $address) {
                    $data = array(
                        'profile_id' => $main_user_id,
                        'address' => trim($address->address),
                        'comment' => trim($address->comment),
                    );
                    ProfileAddress::create($data);
                }
            }

            // обновляем подписки главного контрагента
            if ($this->xml_data->subscribes->count()) {
                $mailChimp = \App::make('\App\Helpers\MailChimpAlfa');

                $listSubscribes = [
                    'xls_weekly' => trim($this->xml_data->subscribes->xls_weekly),
                    'news' => trim($this->xml_data->subscribes->news),
                    'new_items' => trim($this->xml_data->subscribes->new_items),
                    'copy_order' => trim($this->xml_data->subscribes->copy_order),
                ];

                $fieldsApiList = [
                    'xls_weekly' => 'a0cd297c60',
                    'news' => '2da2854a7f',
                    'new_items' => 'dceb303894',
                ];
                foreach ($fieldsApiList as $k => $v) {
                    if (isset($listSubscribes[$k]) and $listSubscribes[$k]) {
                        $mailChimp->addSubscriber($list = $fieldsApiList[$k], $email = $this->xml_data->email, $name = $this->xml_data->company_name);
                    } else {
                        $mailChimp->deleteSubscriber($list = $fieldsApiList[$k], $email = $this->xml_data->email, $name = $this->xml_data->company_name);
                    }
                }

                ProfileSubscribe::updateOrCreate(['profile_id' => $main_user_id], $listSubscribes);
            }

            // обновляем данные дочерних контрагентов
            foreach ($this->xml_data->users->user as $user) {

                // если email пустой, пропускаем
                if(empty(trim($user->email))) continue;

                // если email представителя совпадает с контрагентом, обновляем (дополняем) контрагента
                if (trim($user->email) == trim($this->xml_data->email)) {
                    $data = array(
                        'name' => trim($user->name),
                        'surname' => trim($user->surname),
                    );
                    Profile::where('id', $main_user_id)->update($data);

                    // добавляем контакты
                    $data = array(
                        'profile_id' => $main_user_id,
                        'name' => trim($user->name),
                        'phone' => trim($user->tel_mob),
                    );
                    ProfileContact::create($data);
                    $data = array(
                        'profile_id' => $main_user_id,
                        'name' => trim($user->name),
                        'phone' => trim($user->tel),
                    );
                    ProfileContact::create($data);

                    // пропускаем остальное
                    continue;
                }

                // обновляем остальных

                // берем данные представителя
                $profile = Profile::where('email', trim($user->email))->first();

                // проверяем существование адреса представителя в бд, если нет, пропускаем
                if(!$profile) continue;

                // собираем данные
                $data = array(
                    'name' => trim($user->name),
                    'surname' => trim($user->surname),
                    '1c_id' => 0,
                    'company_name' => trim($this->xml_data->company_name),
                    'company_address' => trim($this->xml_data->company_address),
                    'unp' => trim($this->xml_data->unp),
                    'bank_account' => trim($this->xml_data->bank_account),
                    'bank_name' => trim($this->xml_data->bank_bik),
                    'trade_object' => trim($this->xml_data->trade_object),
                    'type_price' => $type_price,
                    'markup' => $markup,
                    'manager_name' => trim($this->xml_data->manager_name),
                    'manager_email' => trim($this->xml_data->manager_email),
                    'manager_viber' => trim($this->xml_data->manager_viber),
                    'manager_skype' => trim($this->xml_data->manager_skype),
                );
                // обновляем
                Profile::where('email', trim($user->email))->update($data);

                // обновляем контакты представителя
                if ($this->xml_data->contacts->count()) {

                    // удаляем текущие
                    ProfileContact::where('profile_id', $profile->id)->delete();

                    // если не пусто поле phone, собираем данные и добавляем заново
                    if(!empty(trim($user->tel_mob))) {
                        $data = array(
                            'profile_id' => $profile->id,
                            'name' => trim($user->name),
                            'phone' => trim($user->tel_mob),
                        );
                        ProfileContact::create($data);
                    }
                    if(!empty(trim($user->tel))) {
                        $data = array(
                            'profile_id' => $profile->id,
                            'name' => trim($user->name),
                            'phone' => trim($user->tel),
                        );
                        ProfileContact::create($data);
                    }
                }

                // обновляем подписки представителя
                $mailChimp = \App::make('\App\Helpers\MailChimpAlfa');
                $listSubscribes = [
                    'xls_weekly' => trim($user->xls_weekly),
                    'news' => trim($user->news),
                    'new_items' => trim($user->new_items),
                    'copy_order' => trim($user->copy_order),
                ];
                $fieldsApiList = [
                    'xls_weekly' => 'a0cd297c60',
                    'news' => '2da2854a7f',
                    'new_items' => 'dceb303894',
                ];
                foreach ($fieldsApiList as $k => $v) {
                    if (isset($listSubscribes[$k]) and $listSubscribes[$k]) {
                        $mailChimp->addSubscriber($list = $fieldsApiList[$k], $email = trim($user->email), $name = $this->xml_data->company_name);
                    } else {
                        $mailChimp->deleteSubscriber($list = $fieldsApiList[$k], $email = trim($user->email), $name = $this->xml_data->company_name);
                    }
                }
                ProfileSubscribe::updateOrCreate(['profile_id' => $profile->id], $listSubscribes);
            }


            if (!$this->has_error) {
                $this->xml->addChild('success', "Данные по контрагенту - {$this->xml_data->company_name} - обновлены!");
            }
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

    public function postBlock(XMLHelper $XMLHelper, Request $request)
    {
        $mailChimp = \App::make('\App\Helpers\MailChimpAlfa');
        // Поля которые должны прийти в XML
        $requared_fields = ['unp'];

        // Получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        //Аутентификация
        if ($response = $this->xml_auth($this->xml_data, $this->xml)) {
            return $response;
        };

        // Проверка на пустоту
        $this->valid_empty($requared_fields);

        // Должно соответствовать названию полей в базе

        $unp = trim($this->xml_data->unp);


        if (Profile::where('unp', $unp)->count() ) {
            foreach ($this->xml_data->users->user as $user) {

                $profile = Profile::where('email',$user->email)->first();
                $profile->is_blocked = 1;
                $profile->save();
            }

            //\DB::table('sessions')->where('user_id', $profile->user_id)->delete();

            //Profile::where('unp', $unp)->update(['is_ blocked' => 1]);
            $fieldsApiList = [
                'xls_weekly' => 'a0cd297c60',
                'news' => '2da2854a7f',
                'new_items' => 'dceb303894',
                'change_price' => '23fc1a1795'
            ];

            foreach ($fieldsApiList as $k => $v) {
                $mailChimp->deleteSubscriber($list = $fieldsApiList[$k], $email = $profile->email, $name = $profile->company_name);
            }

        } else {
            $this->xml->addChild('error', "Record with user {$unp} not exists!");
            $this->has_error = true;
        }

        if (!$this->has_error) {
            $this->xml->addChild('success', "Record with user {$unp} successfully blocked!");
        }

        $response = new Response($this->xml->asXML(), 200);

        return $response;

    }


    // Блокировать пользователя

    public function getUnBlock(XMLHelper $XMLHelper)
    {
        $data['title'] = 'Разблокирование пользователя';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/users/unblocked';

        $data['desc'] = 'Чтобы разблокировать пользователя  нужно отправить запрос xml запрос который должен обязательно содержать поле:<br>
        <b>УНП компании</b> как на примере ниже<br/>';


        $this->xml->addChild('login', $this->login);
        $this->xml->addChild('password', $this->password);
        $this->xml->addChild('unp', '44114');

        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);

    }

    public function postUnBlock(XMLHelper $XMLHelper, Request $request)
    {

        // Поля которые должны прийти в XML
        $requared_fields = ['unp'];

        // Получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

//        //Аутентификация
        if( $response = $this->xml_auth($this->xml_data, $this->xml) ){
            return $response;
        };

        // Проверка на пустоту
        $this->valid_empty($requared_fields);

        // Должно соответствовать названию полей в базе

        $unp = trim($this->xml_data->unp);


        if (Profile::where('unp', $unp)->count()) {
            foreach ($this->xml_data->users->user as $user) {

                $profile = Profile::where('email',$user->email)->first();
                $profile->is_blocked = 0;
                $profile->save();
            }


        } else {
            $this->xml->addChild('error', "Record with user {$unp} not exists!");
            $this->has_error = true;
        }

        if (!$this->has_error) {
            $this->xml->addChild('success', "Record with user {$unp} successfully unblocked!");
        }

        $response = new Response($this->xml->asXML(), 200);

        return $response;

    }



    // Получение списка новых пользователей
    public function getNewUser(XMLHelper $XMLHelper)
    {

        $data['title'] = 'Получение нового пользователя';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/users/show/new';
        $data['url_api_alt'] = '/api/v1/users/show/post/new/';

        $data['desc'] = 'Чтобы получить нового пользователя   нужно отправить запрос xml запрос который должен обязательно содержать поле логин и пароль<br>
        <b>replay</b> - если повторить последнюю передачу - передаем 1 как на примере';

        $this->xml->addChild('login', $this->login);
        $this->xml->addChild('password', $this->password);
        $this->xml->addChild('replay', 1);

        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);
        return view('api.index', $data);
    }

    public function postNewUser(XMLHelper $XMLHelper, Request $request)
    {

        $requared_fields = [];
        $this->xml_data = $XMLHelper->get_xml($request);

        //Аутентификация
        if ($response = $this->xml_auth($this->xml_data, $this->xml)) {
            return $response;
        };


        if (isset($this->xml_data->replay) and trim($this->xml_data->replay)) {
            $records = Profile::with('address', 'contact')->where('is_replay', 1)->where('is_checked', 1)->where('is_block_info', 0)->get();
        } else {
            $records = Profile::with('address', 'contact')->where('is_send_api_as_new', 0)->where('is_checked', 1)->where('is_block_info', 0)->get();
            Profile::where('is_replay', 1)->update(['is_replay' => 0]);
        }

        //$records = Profile::with('address', 'contact')->where('is_send_api_as_new', 0)->where('is_checked', 1)->where('is_block_info', 0)->get();
        //$firstRecord = Profile::with('address', 'contact')->where('is_send_api_as_new', 0)->where('is_checked', 1)->where('is_block_info', 0)->first();

        if (!count($records)) {
            return $this->xml->addChild('error', "New users don't exists");
        }


        $users = $this->xml->addChild('users');

        foreach ($records as $record) {
            $user = $users->addChild('user');

            $user->addChild('id', $record->id);
            $user->addChild('name', $record->name);
            $user->addChild('surname', $record->surname);
            $user->addChild('email', $record->email);

            $user->addChild('company_name', $record->company_name);
            $user->addChild('company_address', $record->company_address);
            $user->addChild('unp', $record->unp);
            $user->addChild('bank_account', $record->bank_account);
            $user->addChild('bank_bik', $record->bank_name);

            $user->addChild('trade_object', $record->trade_object);
            $user->addChild('shops', $record->shops);
            $user->addChild('coverage_area', $record->coverage_area);
            $user->addChild('is_contragent', $record->is_contragent);
            $user->addChild('is_service', $record->is_service);

            $contacts = $user->addChild('contacts');
            foreach ($record->contact as $c) {
                $contact = $contacts->addChild('contact');
                $contact->addChild('name', $c->name);
                $contact->addChild('phone', $c->phone);
            }

            $addresses = $user->addChild('addresses');
            foreach ($record->address as $a) {
                $address = $addresses->addChild('address');
                $address->addChild('address', $a->address);
                $address->addChild('comment', $a->comment);
            }

            $subscribes = $user->addChild('subscribes');
            $subscribes->addChild('xls_weekly', $record->subscribe->xls_weekly);
            $subscribes->addChild('news', $record->subscribe->news);
            $subscribes->addChild('new_items', $record->subscribe->new_items);
            $subscribes->addChild('copy_order', $record->subscribe->copy_order);
            $subscribes->addChild('change_price', $record->subscribe->change_price);

            $record->is_send_api_as_new = 1;
            $record->is_replay = 1;
            $record->save();
        }

        $this->xml = $XMLHelper->beauty_xml($this->xml);
        \Log::info(Carbon::parse('now') . ' ' . $this->xml);


        $response = new Response($this->xml, 200);
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

}
