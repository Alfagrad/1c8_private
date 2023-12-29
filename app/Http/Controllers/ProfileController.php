<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Profile;
use App\Models\ProfileRepair;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\RequestOptions;

class ProfileController extends Controller
{

    // Учетная запись
    public function index(Request $request)
    {
        $profile = \Auth::user()->profile()->with('partner', 'debt')->first();
        $data['profile'] = $profile;

        return view('profile.index')->with($data);
    }

    public function orders(Request $request)
    {
        $profile = \Auth::user()->profile()->first();
        $data['profile'] = $profile;

        $orders = $profile->orders()->with('items')->orderBy('id', 'desc')->paginate(10);
        $data['orders'] = $orders;

        return view('profile.orders')->with($data);
    }

    public function repairs()
    {
        $profile_unp = profile()->unp;
        $repairs = ProfileRepair::where('profile_unp', $profile_unp)
            ->orderBy('1c_id', 'desc')
            ->get();
        $data['repairs'] = $repairs;

        return view('profile.repairs')->with($data);
    }

    public function subscribes(Request $request){

        $subscribe = auth()->user()->profile()->first()->subscribe;
        $data['subscribe'] = $subscribe;

        return view('profile.subscribes')->with($data);
    }

    public function subscribesSave(Request $request)
    {

        // формируем xml с данными
        $xml = "<?xml version='1.0' encoding='utf-8'?>\n<data version='1.0'>\n";
        // тип отправляемых данных
        $xml .= "<type>user</type>\n";
        // логин (email на сайте)
        $xml .= "<user_email>".\Auth::user()->email."</user_email>\n";
        // рассылки
        $xml .= "<price>".intval($request->xls_weekly)."</price>\n";
        $xml .= "<news>".intval($request->news)."</news>\n";
        $xml .= "<receipt>".intval($request->new_items)."</receipt>\n";
        $xml .= "<order>".intval($request->copy_order)."</order>\n";
        // заканчиваем
        $xml .= "</data>";

// dd($xml);

        // отправка в 1с8
        $client = new GuzzleClient();
        // $credentials = base64_encode($log.':'.$pass);

        try {

            $response = $client->post('http://93.125.106.243/UT_Site/hs/site.exchange/updInf', [
                'connect_timeout' => 10,
                'headers' => [
                    'objectType' => 'users',
                    // 'Authorization' => 'Basic '.$credentials,
                    'Content-Type' => '*/*',
                ],
                'body' => $xml,
            ]);

            // получаем код ответа
            $status_code = $response->getStatusCode();

        } catch (GuzzleException $e) {

            // ничего не делаем, просто пропускаем...
            $status_code = '';

        }

        // если запрос отправлен успешно (код 200)
        if($status_code == 200) {
            $data['note'] = "Данные отправлены. Изменения будут отражены после проверки";
        } else {
            $data['note'] = "Упс! Данные НЕ отправлены! Попробуйте позже или обратитесь к Вашему менеджеру.";
        }

        $subscribe = auth()->user()->profile()->first()->subscribe;
        $data['subscribe'] = $subscribe;

        return view('profile.subscribes')->with($data);

        // $mailChimp = \App::make('\App\H;elpers\MailChimpAlfa');

        // $profile = \Auth::user()->profile()->first();

        // $subscribe = $profile->subscribe;

        // $fields = array('xls_weekly','news','new_items','copy_order', 'change_price');

        // $fieldsApiList = [
        //     'xls_weekly'=> 'a0cd297c60',
        //     'news' => '2da2854a7f',
        //     'new_items' => 'dceb303894',
        //     'change_price' => '23fc1a1795'
        // ];

        // foreach ($fields as $chk) {
        //     $subscribe->setAttribute($chk, $request->has($chk) ? true : false);
        // }

        // foreach ($fieldsApiList as $k => $v) {
        //     if($request->has($k)){
        //         $mailChimp->addSubscriber($list = $fieldsApiList[$k], $email = $profile->email, $name = $profile->company_name);
        //     } else {
        //         $mailChimp->deleteSubscriber($list = $fieldsApiList[$k], $email = $profile->email, $name = $profile->company_name);
        //     }
        // }

        // $subscribe->save();
        // $profile->is_send_api_as_new = 0;
        // $profile->save();

        // return redirect('profile/subscribes');
    }


    public function repairsRefresh(Request $request){

        $repair_code = intval(trim($request->code));

        $repair = ProfileRepair::where('1c_id', $repair_code)->first();

        if(!$repair){

            return 'false';

        }

        return view('profile.partials.repairEl', ['repair' => $repair]);

    }






    // public function profileUpdate(Request $request){
    //     $profile = $request->user()->profile()->with('address', 'contact')->first();
    //     $request->merge([
    //        'email' => $profile->email,
    //        'company_name' => $profile->company_name,
    //     ]);

    //     $requestArray = \App::make('\App\Helpers\RequestArray');
    //     $compareProfile = \App::make('\App\Helpers\CompareProfile');

    //     $request->merge(['name' => $request->get('profile_name')]);


    //     //'email','unp'
    //     //$clearRequest = $request->only('name', 'bank_account', 'bank_name', 'trade_object', 'shops', 'coverage_area');
    //     $clearRequest = $request->only('name', 'bank_account', 'bank_name');

    //     $clearRequest['contacts'] = $requestArray->RequestFieldsToArray($request->contact);
    //     $clearRequest['addresses'] = $requestArray->RequestFieldsToArray($request->addresses);


    //     $compareProfile->setNewProfile($clearRequest);
    //     $compareProfile->setOldProfile($profile);

    //     $differentFields = $compareProfile->RequestFieldsToArray();


    //     if($differentFields ){

    //         // Блокируем реальный профиль
    //         $profile->is_block_info = 1;
    //         $profile->is_send_api_as_new = 0;

    //         $profile->save();

    //         // Берем ID Реального профиля
    //         $request->merge(['real_profile_id' => $profile->id]);

    //         $contacts = $requestArray->RequestFieldsToArray($request->contact);
    //         $contactModels = [];
    //         foreach($contacts as $c ){
    //             $contactModels[] = new TempProfileContact($c);
    //         }


    //         $addresses = $requestArray->RequestFieldsToArray($request->addresses);
    //         $addressModels = [];
    //         foreach($addresses as $a ){
    //             $addressModels[] = new TempProfileAddress($a);
    //         }

    //         // Проверяем чтобы не было такого в TempProfile

    //         //dd($request->all());
    //         // Добавить провеиль ID
    //         $tempProfile = new TempProfile($request->only('name', 'bank_account', 'bank_name', 'real_profile_id'));
    //         $tempProfile ->email = $profile->email;
    //         $tempProfile ->company_name = $profile->company_name;
    //         $tempProfile ->company_address = $profile->company_address;
    //         $tempProfile ->unp = $profile->unp;

    //         $tempProfile ->is_checked = 0;
    //         $tempProfile ->save();


    //         $tempProfile->contact()->saveMany($contactModels);
    //         $tempProfile->address()->saveMany($addressModels);

    //         if(!$profile->manager_email){
    //             //    return redirect('/pages/thanks_message');
    //             $profile->manager_email =  setting('email_to_other');
    //         }
    //         //dd($profile->manager_email);
    //         $email_headers['subject'] = 'Изменение регистрационных данных';
    //         $email_headers['email_to'] = $profile->manager_email; //$user->email;
    //         \Mail::send('emails.reg_compare', [ 'realProfile' => $profile, 'tempProfile' => $tempProfile, 'differentFields' => $differentFields ], function($message) use ($email_headers)
    //         {
    //             //$message->from($email_headers['email_from'], $email_headers['headers_from']);
    //             $message->to($email_headers['email_to'])->subject($email_headers['subject']);
    //         });


    //     }
    //     return redirect('/profile/index');

    // }


    // public function accept($profileId){

    //     if($profileId){
    //         // Проверить на заблокированность
    //         $realProfile = Profile::where('id', $profileId)->first();
    //         $fields = [ 'name', 'bank_account', 'bank_name'/*, 'trade_object', 'shops', 'coverage_area' */];
    //         $tempProfile = TempProfile::where('real_profile_id', $profileId)->first();

    //         if(!$tempProfile){
    //             return redirect('/');
    //         }

    //         $tempProfileFields = TempProfile::where('real_profile_id', $profileId)->select($fields)->first()->toArray();
    //         Profile::where('id', $profileId)->update($tempProfileFields);

    //         ProfileContact::where('profile_id', $realProfile->id)->delete();
    //         ProfileAddress::where('profile_id', $realProfile->id)->delete();

    //         $tempContacts = TempProfileContact::where('temp_profile_id', $tempProfile->id)->select(['name', 'phone'])->get()->toArray();
    //         $contactModels = [];
    //         foreach($tempContacts as $c ){
    //             $contactModels[] = new ProfileContact($c);
    //         }

    //         $tempAddress = TempProfileAddress::where('temp_profile_id', $tempProfile->id)->select(['address', 'comment'])->get()->toArray();
    //         $addressModels = [];
    //         foreach($tempAddress as $c ){
    //             $addressModels[] = new ProfileAddress($c);
    //         }

    //         $realProfile->contact()->saveMany($contactModels);
    //         $realProfile->address()->saveMany($addressModels);

    //         $tempProfile = TempProfile::where('real_profile_id', $profileId)->first();
    //         $tempContacts = TempProfileContact::where('temp_profile_id', $tempProfile->id)->select(['name', 'phone'])->get()->toArray();
    //         $tempAddress = TempProfileAddress::where('temp_profile_id', $tempProfile->id)->select(['address', 'comment'])->get()->toArray();

    //         $this->clearTemp($profileId);
    //         Profile::where('id', $profileId)->update(['is_block_info' => 0]);

    //         return redirect('/');

    //     }
    //     return redirect('/');
    //     // Получаем реальный профиль
    //     // Получаем темп профиль.
    //     // Меняем все поля из темп профиля в реальном.
    //     // Удаляем все контакты приводя к массиву
    //     // Добавляем все контакты

    // }

    // private function clearTemp($profileId){
    //     // снять блокировку
    //     $tempProfile = TempProfile::where('real_profile_id', $profileId)->first();
    //     if(!$tempProfile){
    //         return false;
    //     }
    //     TempProfileContact::where('temp_profile_id', $tempProfile->id)->delete();
    //     TempProfileAddress::where('temp_profile_id', $tempProfile->id)->delete();
    //     TempProfile::where('real_profile_id', $profileId)->delete();
    //     return true;
    // }

    // public function refuse($profileId){
    //     $this->clearTemp($profileId);
    //     Profile::where('id', $profileId)->update(['is_block_info' => 0]);
    //     return redirect('/');
    // }





    // public function searchRepairs(Request $request){

    //     if(\Auth::user()->role_id != '2') {

    //         $data = $this->getUserData($request);

    //         return view('profile.repairs_search')->with($data);

    //     } else {

    //         return redirect()->back();

    //     }
    // }




    // Возможно потребуется контроллер для аякса для автопоиска по заказам если больше трех символов


    // public function addressAdd(Request $request){
    //     $data = $this->getUserData($request);
    //     $profile = $request->user()->profile()->with('subscribe')->first();
    //     $addressModel = new ProfileAddress($request->only(['address', 'comment']));
    //     $address = $profile->address()->save($addressModel);
    //     $profile->is_send_api_as_new = 0;
    //     $profile->save();

    //     return view('profile.partials.ajaxAddressOption', ['address' => $address])->with($data);

    // }

}
