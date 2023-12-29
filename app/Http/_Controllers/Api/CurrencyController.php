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
use TCG\Voyager\Models\Setting;

class CurrencyController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * @param XMLHelper $XMLHelper
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreateOrUpdate(XMLHelper $XMLHelper)
    {
        //dd($this->xml);

        $data['title'] = 'Обновление курсов валют';
        $data['verb'] = 'Все запросы должны отправляться POST';
        $data['url_api'] = '/api/v1/currency/createOrUpdate';

        $data['desc'] = '<p>Описание полей:<br>
        <ul>
            <li><b>usd</b> - Цена доллара<b>FLOAT, Обязательное</b></li>
            <li><b>usd_for_mr</b> - Цена доллара для мин реализации<b>FLOAT, Обязательное</b></li>

        </ul>
        </p>';


        $this->xml->addChild('login', $this->login);
        $this->xml->addChild('password', $this->password);

        $this->xml->addChild('usd', '1.97');
        $this->xml->addChild('usd_for_mr', '1.95');

        $data['test_xml'] = $XMLHelper->beauty_xml($this->xml);

        return view('api.index', $data);
    }

    /**
     * @param XMLHelper $XMLHelper
     * @param Request   $request
     *
     * @return Response
     */
    public function postCreateOrUpdate(XMLHelper $XMLHelper, Request $request){
        // Поля которые должны прийти в XML
        $requared_fields = [ 'usd', 'usd_for_mr', ];

        $this->xml_data = $XMLHelper->get_xml($request);
        if ($response = $this->xml_auth($this->xml_data, $this->xml)) {
            return $response;
        };

        $this->valid_empty($requared_fields);

        $isCheck = false;
        if( (setting('header_usd') != $this->xml_data->usd) or (setting('header_usd_mrc') != $this->xml_data->usd_for_mr)){
            $isCheck = true;
        }


        \DB::table('settings')
            ->where('id', 15)
            ->update(['value' => trim($this->xml_data->usd)]);

        \DB::table('settings')
            ->where('id', 16)
            ->update(['value' => trim($this->xml_data->usd_for_mr)]);


        if($isCheck){
            $changeCurrencyHelper = \App::make('\App\Helpers\ChangeCurrencyHelper');
            $changeCurrencyHelper->reCheck();
        }




        if (!$this->has_error) {
            $this->xml->addChild('success', "Record with currency successfully {$this->type_action}!");
        }

        $response = new Response($this->xml->asXML(), 200);
        return $response;
    }


    /**
     * @param $req_fields
     */
    function valid_empty($req_fields){
        foreach($req_fields as $f){
            if(trim($this->xml_data->{$f}) == ''){
                $this->xml->addChild('error', "Field {$f} cannot be empty!");
                $this->has_error = true;
            }
        }
    }








}
