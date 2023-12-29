<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\ProfileRepair;

class ServicePageController extends Controller
{
    public function index()
    {
        $service = Page::find(23);

        return view('service.open', compact('service'));
    }

    public function serviceDocs()
    {
        $service = Page::find(24);

        return view('open_service_docs', compact('service'));
    }

    public function eripPay()
    {
        $service = Page::find(25);

        return view('open_service_erip', compact('service'));
    }

    public function repairStatus(Request $request)
    {
        if(\Route::currentRouteName() == 'openRepairStatus') {
            return view('open_service_repair_status');
        } else {
            // $data = $this->getUserData($request);
            return view('close_service_repair_status');
        }
    }

    public function ajaxGetRepair(Request $request)
    {

        // валидация
        $this->validate($request, [
            'receipt_number' => 'string | max:10 | alpha_num | nullable',
            'serial_number' => 'string | max:30 | alpha_dash | nullable',
            'search' => 'string | max:7 | required',
        ]);

        // берем номер квитанции
        $receipt_number = $request->receipt_number;

        // берем серийный номер
        $serial_number = $request->serial_number;

        // берем тип поиска
        $search = $request->search;

        if($search == 'receipt' && intval($receipt_number)) {

            // ищем ремонт по номеру квитанции
            $repair = ProfileRepair::where('1c_id', intval($receipt_number))->first();

            // если есть
            if($repair) {
                return view('general.ajax-repair-status', compact('repair'));
            } else {
                return "Ремонт по квитанции № {$request->receipt_number} не найден!";
            }

        }

        if($search == 'serial' && $serial_number) {

            // ищем ремонт по номеру квитанции
            $repair = ProfileRepair::where('serial', 'like', '%'.$serial_number.'%')->orderBy('1c_id', 'desc')->first();

            // если есть
            if($repair) {
                return view('general.ajax-repair-status', compact('repair'));
            } else {
                return "Ремонт по серийному № {$request->serial_number} не найден!";
            }

        }

        if(!intval($receipt_number) || !$serial_number) {
            return "Некорректный запрос";
        }

    }
}
