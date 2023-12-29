<?php

namespace App\Http\Controllers\Api;

use App\Item;
use App\ItemAction;
use App\ItemImage;
use App\Profile;
use App\ProfileAddress;
use App\ProfileContact;
use App\ProfileDept;
use App\ProfileRepair;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Helpers\XMLHelper;
use Illuminate\Http\Response;

class DeptController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    public function getCreateOrUpdate(XMLHelper $XMLHelper)
    {
        //dd($this->xml);

        $data['title'] = 'Добавление и обновление задолженности';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/depts/createOrUpdate';

        $data['desc'] = '<p>Описание полей:<br>
        <ul>
            <li><b>1c_id</b> - ID 1c<b>STRING, Обязательное</b></li>
            <li><b>user_unp</b> - УНП компании<b>STRING, Обязательное</b></li>
            <li><b>depts</b> -  Привязанные задолженности<b></b></li>
            <li><b>dept</b> - Задолженность <b></b>
                <ul>
                    <li><b>realization_date</b> - Дата реализации. <b>Unix Time Stamp, Обязательное</b></li>
                    <li><b>realization_sum</b> - Сумма реализации. <b>FLOAT, Обязательное</b></li>        
                    <li><b>pay_date</b> - Срок оплаты. <b>Unix Time Stamp, Обязательное</b></li>
                    <li><b>pay_delay</b> - Просрок, в днях<b>INT, Обязательное</b></li>
                    <li><b>dept</b> - Задолженасть в рублях<b>FLOAT, Обязательное</b></li>
                </ul>
            </li>
        </ul>
        </p>';


        $this->xml->addChild('login', $this->login);
        $this->xml->addChild('password', $this->password);
        $this->xml->addChild('user_unp', '123456789');

        $depts = $this->xml->addChild('depts');
        $dept  = $depts->addChild('dept');
        $dept->addChild('realization_date', '1490278466');
        $dept->addChild('realization_sum', '3600.00');
        $dept->addChild('pay_date', '1490278466'); // Тип данных - text, bool, int, float
        $dept->addChild('pay_delay', '15'); //
        $dept->addChild('dept', '1500.00'); //


        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);
    }

    public function postCreateOrUpdate(XMLHelper $XMLHelper, Request $request){
        // Поля которые должны прийти в XML
        $requared_fields = [ 'user_unp' ];

        $this->xml_data = $XMLHelper->get_xml($request);
        if ($response = $this->xml_auth($this->xml_data, $this->xml)) {
            return $response;
        };

        $this->valid_empty($requared_fields);


        $user_unp = trim($this->xml_data->user_unp);
        $profiles = Profile::where('unp', $user_unp)->get();


        foreach ($profiles as $profile) {
            \DB::table('profile_depts')->where('profile_id', $profile->id)->delete();

            if($this->xml_data->depts->count()){
                foreach ($this->xml_data->depts->dept as $dept){
                    $data = array(
                        'profile_id' => $profile->id,
                        'realization_date' => trim($dept->realization_date),
                        'realization_sum' => trim($dept->realization_sum),
                        'pay_date' => trim($dept->pay_date),
                        'pay_delay' => trim($dept->pay_delay),
                        'dept' => trim($dept->dept),
                        'is_active' => 1
                    );

                    if (!$this->has_error) {
                        \DB::table('profile_depts')->insert($data);
                    }

                }
            }
        }





/*
        $data = array(
            'profile_id' => $profile->id,
            'realization_date' => trim($this->xml_data->realization_date),
            'realization_sum' => trim($this->xml_data->realization_sum),
            'pay_date' => trim($this->xml_data->pay_date),
            'pay_delay' => trim($this->xml_data->pay_delay),
            'dept' => trim($this->xml_data->dept),
            'is_active' => 1
        );
        
        
        if (!$this->has_error) {
            \DB::table('profile_depts')->insert($data);
            //ProfileDept::updateOrCreate( ['1c_id'=>$id_1c], $data);
        }
*/
        if (!$this->has_error) {
            $this->xml->addChild('success', "Record with dept  {$user_unp} successfully {$this->type_action}!");
        }

        $response = new Response($this->xml->asXML(), 200);
        return $response;
    }



    public function getDelete(XMLHelper $XMLHelper)
    {
        $data['title'] = 'Удаление задолженности';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/depts/delete';

        $data['desc'] = 'Чтобы удалить задолженности у компании, нужно отправить запрос xml запрос который должен обязательно содержать поле:<br>
        <b>УНП компании</b> как на примере ниже<br/>';


        $this->xml->addChild('login', $this->login);
        $this->xml->addChild('password', $this->password);
        $this->xml->addChild('unp', '44114');

        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);

    }


    public function postDelete(XMLHelper $XMLHelper, Request $request){

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

        $user_unp = trim($this->xml_data->unp);
        $profiles = Profile::where('unp', $user_unp)->get();

        foreach ($profiles as $profile) {
            \DB::table('profile_depts')->where('profile_id', $profile->id)->delete();

        }
        if (!$profiles){
            return $this->xml->addChild('error', "User is don't exists");
        }




        if(!$this->has_error){
            $this->xml->addChild('success', "Record with dept {$user_unp} with actions and images successfully deleted!");
        }

        $response = new Response($this->xml->asXML(), 200);

        return $response;

    }


    public function getTruncate(XMLHelper $XMLHelper)
    {

        $data['title'] = 'Очистка таблицы с товарами';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/depts/truncate';

        $data['desc'] = 'Чтобы очистить таблицу нужно отправить запрос xml запрос который должен обязательно содержать поле:<br>
        <b>Удалить все </b> как на примере ниже<br/>';

        $this->xml->addChild('login', $this->login);
        $this->xml->addChild('password', $this->password);
        $this->xml->addChild('delete', 'all');

        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);

    }


    public function postTruncate(XMLHelper $XMLHelper, Request $request){

        // Получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        //Аутентификация
        if( $response = $this->xml_auth($this->xml_data, $this->xml) ){
            return $response;
        };

        if($this->xml_data->delete == 'all' ){
            \DB::table('profile_depts')->truncate();
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
