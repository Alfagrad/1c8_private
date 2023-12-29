<?php

namespace App\Http\Controllers\Api_1c8;

use App\Events\UserCheckedByManager;
use App\Services\RocketSms;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Helpers\XMLHelper;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client as GuzzleClient;
use App\Jobs\MailChimpJob;


use App\Models\User;
use App\Models\Profile;
use App\Models\Manager;
use App\Models\ProfileSubscribe;


class UserController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    // создание или обновление Юзера *******************************************
    public function postUserUpdate(XMLHelper $XMLHelper, Request $request)
    {

        // пример xml ()
        $xml = '
<?xml version="1.0" encoding="UTF-8"?>
<data  version="1.0">
<user>12@test.by</user>
<email>12@test.by</email>
<name>Galaxy A8</name>
<unp>291049917</unp>
<role>Клиент</role>
<company>АкселТрим</company>
<partner>ce09974e-121c-11ec-9891-005056a0b35f</partner>
<price>1</price>
<news>0</news>
<receipt>0</receipt>
<order>1</order>
<phone>+375 (17) 291-28-97</phone>
<mobile>+375 (29) 982-38-71</mobile>
<position>тестовый Директор</position>
</data>
        ';
        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        $requared_fields = ['user', 'name', 'unp', 'role', 'company', 'partner', 'phone', 'price', 'news', 'receipt', 'order',];

        // проверка на пустоту
        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        $email = trim($this->xml_data->user);

        $user = User::where('email', $email)->first();
        if (!$user) {
            return new Response("Ошибка! Юзера {$email} НЕ существует!", 404);
        }

        $role = trim($this->xml_data->role);
        if ($role == 'Клиент') {
            $user->site_role = 111;
        } elseif ($role == 'Сервис') {
            $user->site_role = 222;
        }
        $user->name = trim($this->xml_data->name);
        $user->update();

        $profile = Profile::where('user_id', $user->id)->first();
        $profile->role = $role;
        $profile->name = trim($this->xml_data->name);
        $profile->email = $email;
        $profile->company_name = trim($this->xml_data->company);
        $profile->unp = trim($this->xml_data->unp);
        $profile->phone = trim($this->xml_data->phone);
        $profile->phone_mob = trim($this->xml_data->mobile);
        $profile->position = trim($this->xml_data->position);
        $profile->partner_uuid = trim($this->xml_data->partner);
        $profile->is_blocked = 0;
        $profile->deleted = 0;

        // если это новый юзер
        if ($profile->is_checked == 0) {
            $profile->is_checked = 1;
            event(new UserCheckedByManager($profile->phone_mob, $email));
//            if ($mobile = $profile->phone_mob) {
//                app(RocketSms::class)->send($mobile, 'Ваша регистрация на alfastok.by подтверждена');
//            }

            // отправляем письмо
//            $email_headers['subject'] = 'Ваша регистрация на сайте Альфасток';
//            $email_headers['email_to'] = $email;
//
//            $email_headers['email_from'] = setting('email_from');
//            $email_headers['headers_from'] = 'Alfastok';
//
//            \Mail::send('emails.regAccept', ['profile' => $profile], function ($message) use ($email_headers) {
//                $message->from($email_headers['email_from'], $email_headers['headers_from']);
//                $message->to($email_headers['email_to'])->subject($email_headers['subject']);
//            });

        }

        $profile->update();

        ProfileSubscribe::updateOrCreate([
            'profile_id' => $profile->id,
            $this->getProfileSubscriberData($profile, $this->xml_data)
        ]);

        //TODO !!! РАЗОБРАТЬСЯ С MAILCHIMP !!!
        // // добавление рассылок в MailChimp в очереди
        // dispatch(new MailChimpJob($email, $name));

        // // добавляем в подписки
        // if($user->site_role == '111') {
        //     $mailChimp = \App::make('\App\Helpers\MailChimpAlfa');
        //     $fieldsApiList = [
        //         'xls_weekly'=> 'a0cd297c60',
        //         'news' => '2da2854a7f',
        //         'new_items' => 'dceb303894',
        //         'change_price' => '23fc1a1795'
        //     ];
        //     foreach ($fieldsApiList as $k => $v) {
        //         $mailChimp->addSubscriber($list = $fieldsApiList[$k], $email = $profile->email, $name = $profile->company_name);
        //     }
        // }

        return new Response("Юзер {$email} обновлен успешно!", 200);
    }

    // создание или обновление Сотрудника *******************************************
    public function postManagerCreateOrUpdate(XMLHelper $XMLHelper, Request $request)
    {
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        $requared_fields = ['user', 'name',];

        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        // берем емайл (логин)
        $email = trim($this->xml_data->user);
        $name = trim($this->xml_data->name);

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = new User;

            $password = str_slug(str_random(8));

            $user->password = bcrypt($password);
            $user->email = $email;
            $user->name = $name;
            $user->role_id = 5;
            $user->site_role = 111;

            $result_user = $user->save();

            // !!! нужно отправить письмо с паролем !!!
        } else { // если существует
            // обновляем только имя
            $user->name = $name;
            $result_user = $user->update();
        }

        // если юзер не создан(обновлен)
        if (!$result_user) {
            new Response("Ошибка! Сотрудник {$name} НЕ добавлен(обновлен)!", 400);
        }

        // данные для таблицы Профайлы
        $data = array(
            'user_id' => $user->id,
            'role' => "Сотрудник",
            'name' => $name,
            'email' => $email,
            'unp' => '',
            'phone_mob' => trim($this->xml_data->mobile),
            'status' => 1,
            'is_checked' => 1,
            'is_blocked' => 0,
            'deleted' => 0,
        );

        // сохраняем или обновляем
        $result_profile = Profile::updateOrCreate(['user_id' => $user->id], $data);

        // если профайл создан(обновлен)
        if (!$result_profile) {
            return new Response("Ошибка! Сотрудник {$name} НЕ добавлен(обновлен)!", 400);
        }

        // данные для таблицы Менеджеры
        $data = array(
            'profile_id' => $result_profile->id,
            'name' => $name,
            'email' => $email,
            'phone' => trim($this->xml_data->mobile),
            'viber' => trim($this->xml_data->viber),
            'skype' => trim($this->xml_data->skype),
            'bitrix_code' => intval(trim($this->xml_data->bitrix)),
            'department' => intval(trim($this->xml_data->seller)),
            'assistant' => trim($this->xml_data->assistant),
        );

        if (!Manager::updateOrCreate(['profile_id' => $result_profile->id], $data)) {
            return new Response("Ошибка! Сотрудник {$name} НЕ добавлен(обновлен)!", 400);
        }

        // подписка на рассылку
        $data = $this->getProfileSubscriberData($result_profile, $this->xml_data, false);

        // сохраняем или обновляем
        $result_subscribe = ProfileSubscribe::updateOrCreate(['profile_id' => $result_profile->id], $data);

        // // добавляем в подписки
        // if($user->site_role == '111') {
        //     $mailChimp = \App::make('\App\Helpers\MailChimpAlfa');
        //     $fieldsApiList = [
        //         'xls_weekly'=> 'a0cd297c60',
        //         'news' => '2da2854a7f',
        //         'new_items' => 'dceb303894',
        //         'change_price' => '23fc1a1795'
        //     ];
        //     foreach ($fieldsApiList as $k => $v) {
        //         $mailChimp->addSubscriber($list = $fieldsApiList[$k], $email = $profile->email, $name = $profile->company_name);
        //     }
        // }


        return new Response("Сотрудник {$name} добавлен(обновлен) успешно!", 200);
    }

    // удаление Юзера ************************************************
    public function deleteUser(XMLHelper $XMLHelper, Request $request)
    {

        // приходящая xml для deleteUser (пример)
        $xml = '
<?xml version="1.0" encoding="UTF-8"?>
<data  version="1.0">
<user>alexander.kovalev@alfagrad.com</user>
</data>
        ';

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // поля в XML, которые должны быть заполнены
        $requared_fields = [
            'user',
        ];

        // проверка на пустоту
        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        // берем емайл
        $email = trim($this->xml_data->user);

        // берем юзера
        $user = User::where('email', $email)->first();

        // если существует
        if ($user) {

            // берем id юзера
            $user_id = $user->id;

            // берем профайл
            $profile = Profile::where('user_id', $user_id)->first();

            // если существует
            if ($profile) {

                // берем id профайла
                $profile_id = $profile->id;

                // узнаем новая ли регистрация
                $checked = $profile->is_checked;

                // если новая регистрация
                if ($checked == 0) {

                    // удаляем юзера
                    $user->delete();

                    // удаляем профайл
                    $profile->delete();

                    // отправляем письмо юзеру
                    $email_headers['email_from'] = setting('email_from');
                    $email_headers['headers_from'] = 'Alfastok';

                    $email_headers['subject'] = 'Ваша регистрация на сайте Альфасток';
                    $email_headers['email_to'] = $email;

                    \Mail::send('emails.regRefuse', ['profile' => $profile], function ($message) use ($email_headers) {
                        $message->from($email_headers['email_from'], $email_headers['headers_from']);
                        $message->to($email_headers['email_to'])->subject($email_headers['subject']);
                    });

                    // удаляем из таблицы подписок
                    ProfileSubscribe::where('profile_id', $profile_id)->delete();

                    // !!! ЕЩЕ УДАЛИТЬ ИЗ MailChimp

                    $response = new Response("Юзер {$email} удален успешно!", 200);

                } else { //

                    // "почечаем" как удаленый
                    $profile->update([
                        'deleted' => 1,
                    ]);

                    // обнуляем подписки в таблице подписок
                    ProfileSubscribe::where('profile_id', $profile_id)->update([
                        'xls_weekly' => 0,
                        'news' => 0
                    ]);

                    $response = new Response("Юзер {$email} помечен как удаленный!", 200);

                }

            } else {
                $response = new Response("Ошибка! Юзер {$email} НЕ удален! Попробуйте еще раз или обратитесь к разработчику сайта.", 200);
            }

        } else {
            $response = new Response("Ошибка. Юзер {$email} НЕ существует!", 200);
        }

        return $response;
    }

    //     public function deleteUser(XMLHelper $XMLHelper, Request $request)
//     {

    //         // получаем xml данные
//         $this->xml_data = $XMLHelper->get_xml($request);

    //         // авторизация
//         if ($response = $this->xml_auth($request)) {
//             return $response;
//         }

    //         // поля в XML, которые должны быть заполнены
//         $requared_fields = [
//             'user',
//         ];

    //         // проверка на пустоту
//         if ($response = $this->valid_empty($requared_fields)) {
//             return $response;
//         }

    //         // берем емайл
//         $email = trim($this->xml_data->user);

    //         // берем юзера
//         $user = User::where('email', $email)->first();

    //         // если существует
//         if($user) {

    //             // берем id юзера
//             $user_id = $user->id;

    //             // удаляем юзера
//             $user->delete();

    //             // берем профайл
//             $profile = Profile::where('user_id', $user_id)->first();

    //             // если существует
//             if($profile) {

    //                 // берем id профайла
//                 $profile_id = $profile->id;

    //                 // берем тип клиента
//                 $role = $profile->role;

    //                 // узнаем новая ли регистрация
//                 $checked = $profile->is_checked;

    //                 // удаляем профайл
//                 $profile->delete();

    //                 // если новая регистрация, отправляем письмо юзеру
//                 if($checked == 0) {
//                     $email_headers['email_from'] = setting('email_from');
//                     $email_headers['headers_from'] = 'Alfastok';

    //                     $email_headers['subject'] = 'Ваша регистрация на сайте Альфасток';
//                     $email_headers['email_to'] = $email;

    //                     \Mail::send('emails.regRefuse', [ 'profile' => $profile], function($message) use ($email_headers)
//                     {
//                         $message->from($email_headers['email_from'], $email_headers['headers_from']);
//                         $message->to($email_headers['email_to'])->subject($email_headers['subject']);
//                     });
//                 }

    //                 // если тип клиента - Сотрудник
//                 if ($role == "Сотрудник") {
//                     // удаляем из таблицы сотрудников
//                     Manager::where('profile_id', $profile_id)->delete();
//                 }

    //                 // удаляем из таблицы подписок
//                 ProfileSubscribe::where('profile_id', $profile_id)->delete();

    //                 // !!! ЕЩЕ УДАЛИТЬ ИЗ MailChimp
//             }

    //             $response = new Response("Юзер {$email} удален успешно!", 200);
//         } else {
//             $response = new Response("Ошибка. Юзер {$email} НЕ существует!", 200);
//         }

    //         return $response;
//     }


    // блокировка юзера *************************************************
    public function blockUser(XMLHelper $XMLHelper, Request $request)
    {

        // приходящая xml для blockUser (пример)
        $xml = '
<?xml version="1.0" encoding="UTF-8"?>
<data  version="1.0">
<user>alexander.kovalev@alfagrad.com</user>
</data>
        ';

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        $requared_fields = ['user',];

        if ($response = $this->valid_empty($requared_fields)) {
            return $response;
        }

        $email = trim($this->xml_data->user);

        $user = User::where('email', $email)->first();
        if (!$user) {
            return new Response("Ошибка. Юзер {$email} НЕ существует!", 404);
        }

        $profile = Profile::where('user_id', $user->id)->first();
        if (!$profile) {
            return new Response("Ошибка. Юзер {$email} НЕ заблокирован! Попробуйте еще раз или обратитесь к разработчику сайта", 400);
        }
        $profile->update(['is_blocked' => 1]);
        return new Response("Юзер {$email} заблокирован успешно!", 200);
    }

    // синхронизация Контрагентов *******************************************
    public function syncContragents(XMLHelper $XMLHelper, Request $request)
    {

        return new Response("Сотрудники обновлены успешно!!!!!!", 200);

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если не пусто
        if ($this->xml_data->user->count()) {

            // устанавливаем блокировку для всех юзеров
            Profile::where('is_blocked', '!=', 1)
                ->update(['is_blocked' => 1]);

            foreach ($this->xml_data->user as $user) {

                // обновляем (создаем) юзера
                $new_user = User::updateOrCreate(
                    ['email' => trim($user->user)],
                    ['name' => trim($user->name)]
                );

                // поле email в xml пока игнорируем!

                // обновляем (создаем) профайл ********************
                // роль
                if (trim($user->role)) {
                    $role = trim($user->role);
                } else {
                    $role = "Клиент";
                }
                $new_profile = Profile::updateOrCreate(
                    ['user_id' => $new_user->id],
                    [
                        'role' => $role,
                        'is_blocked' => 0,
                        'name' => trim($user->name),
                        'email' => trim($user->user),
                        'company_name' => trim($user->company),
                        'unp' => trim($user->unp),
                        'phone' => trim($user->phone),
                        'phone_mob' => trim($user->mobile),
                        'position' => trim($user->position),
                        'partner_uuid' => trim($user->partner),
                        'status' => 1,
                        'is_checked' => 1,
                    ]
                );

                // данные для рассылок
//                $data = [
//                    'xls_weekly' => intval($user->price),
//                    'news' => intval($user->news),
//                    'new_items' => intval($user->receipt),
//                    'copy_order' => intval($user->order),
//                ];
                $data = $this->getProfileSubscriberData($new_profile, $user, true);

                // обновляем рассылки в БД
                $new_subscr = ProfileSubscribe::updateOrCreate(
                    ['profile_id' => $data['profile_id']],
                    $data
                );

                // !!! РАЗОБРАТЬСЯ С MAILCHIMP !!!
                // // добавление рассылок в MailChimp в очереди
                // dispatch(new MailChimpJob($email, $name));

            }

            $response = new Response("Контрагенты обновлены успешно!", 200);

        } else {
            $response = new Response("Ошибка! Нет контрагентов для обновления!", 200);
        }

        return $response;
    }

    // синхронизация Контрагентов *******************************************
    public function syncEmployees(XMLHelper $XMLHelper, Request $request)
    {
        $this->xml_data = $XMLHelper->get_xml($request);
        return new Response("Сотрудники обновлены успешно!11111", 200);
        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если не пусто
        if ($this->xml_data->user->count()) {

            foreach ($this->xml_data->user as $user) {

                // берем емайл (логин)
                $email = trim($user->user);
                // берем имя
                $name = trim($user->name);

                // берем юзера
                $new_user = User::where('email', $email)->first();


                // если не существует
                if (!$new_user) {
                    // создаем нового
                    $new_user = new User;

                    // данные
                    $new_user->email = $email;
                    $new_user->name = $name;
                    $new_user->role_id = 5;
                    $new_user->site_role = 111;

                    // сохраняем
                    $result_user = $new_user->save();

                } else { // если существует

                    // обновляем только имя
                    $new_user->name = $name;
                    $result_user = $new_user->update();

                }

                // если юзер создан(обновлен)
                if ($result_user) {

                    // данные для таблицы Профайлы
                    $data = array(
                        'user_id' => $new_user->id,
                        'role' => "Сотрудник",
                        'name' => $name,
                        'email' => $email,
                        'unp' => '',
                        'phone_mob' => trim($user->mobile),
                        'status' => 1,
                        'is_checked' => 1,
                        'is_blocked' => 0,
                        'deleted' => 0,
                    );

                    // сохраняем или обновляем
                    $result_profile = Profile::updateOrCreate(['user_id' => $new_user->id], $data);

                    // данные для таблицы Менеджеры
                    $data = array(
                        'profile_id' => $result_profile->id,
                        'name' => $name,
                        'email' => $email,
                        'phone' => trim($user->mobile),
                        'viber' => trim($user->viber),
                        'skype' => trim($user->skype),
                        'bitrix_code' => intval(trim($user->bitrix)),
                        'department' => intval(trim($user->seller)),
                        'assistant' => trim($user->assistant),
                    );

                    $result_manager = Manager::updateOrCreate(['profile_id' => $result_profile->id], $data);
                    $result_subscribe = ProfileSubscribe::updateOrCreate(['profile_id' => $result_profile->id], $this->getProfileSubscriberData($result_profile, $user));

                }

                // !!! РАЗОБРАТЬСЯ С MAILCHIMP !!!
                // // добавление рассылок в MailChimp в очереди
                // dispatch(new MailChimpJob($email, $name));

            }


            $response = new Response("Сотрудники обновлены успешно!", 200);

        } else {
            $response = new Response("Ошибка! Нет сотрудников для обновления!", 200);
        }


        return $response;
    }

    private function getProfileSubscriberData($profile, $data, bool $isNeedCopyOrder = true): array
    {
        $data = [
            'profile_id' => $profile->id,
            'xls_weekly' => intval(trim($data->price)),
            'news' => intval(trim($data->news)),
            'new_items' => intval(trim($data->receipt))
        ];
        if($isNeedCopyOrder){
            $data = array_merge($data, ['copy_order' => intval(trim($data->order))]);
        }
        return $data;
    }

}
