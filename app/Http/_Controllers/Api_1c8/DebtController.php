<?php

namespace App\Http\Controllers\Api_1c8;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Helpers\XMLHelper;

use App\Models\Profile;
use App\Models\ProfileDebt;

class DebtController extends BaseController
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

        // если в запросе есть партнеры
        if (isset($this->xml_data->partners->partner) && $this->xml_data->partners->partner->count()) {

            foreach ($this->xml_data->partners->partner as $partner) {

                // берем unp контрагента
                $unp = $partner->unp;

                // проверяем, есть ли контрагент с таким УНП
                $contr = Profile::where('unp', $unp)->first(['id']);

                // если нет
                if (!$contr) {
                    // пропускаем итерацию
                    continue;
                }

                // если у контрагента есть долги
                if (isset($partner->debts->debt) && $partner->debts->debt->count()) {

                    // удаляем информацию о долге
                    ProfileDebt::where('unp', $unp)->delete();

                    foreach ($partner->debts->debt as $debt) {

                        // собираем данные
                        $data = [
                            'unp' => $unp,
                            'realization_date' => trim($debt->realization_date),
                            'realization_sum' => doubleval(trim($debt->realization_sum)),
                            'pay_date' => trim($debt->pay_date),
                            'sum' => doubleval(trim($debt->sum)),
                        ];

                        ProfileDebt::create($data);
                    }
                }
            }

            $response = new Response("Пакет принят сайтом. Долги установлены успешно.", 200);

        } else {
            $response = new Response("Ошибка! Нет данных в xml.", 200);
        }

        return $response;

    }

    public function postTruncate(XMLHelper $XMLHelper, Request $request)
    {

        // получаем xml данные
        $this->xml_data = $XMLHelper->get_xml($request);

        // авторизация
        if ($response = $this->xml_auth($request)) {
            return $response;
        }

        // если в запросе есть пустой тег delete
        if (isset($this->xml_data->delete) && empty(trim($this->xml_data->delete))) {

            // очищаем таблицу
            ProfileDebt::truncate();

            $response = new Response("Таблица очищена успешно.", 200);

        } else {

            $response = new Response("Таблица НЕ очищена. Тег delete отсутствует или не пустой.", 200);

        }

        return $response;
    }


}
