<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Order;
use App\ServiceConclusion;
use App\ServiceAct;
use PDF;

class ServiceController extends Controller
{
    public function makeConclusion(Request $request) {

        $data = $this->getUserData($request);
        $data['order'] = Order::where('id', $request->order_id)->first();

        return view('service.make_conclusion', $data);
    }

    public function saveConclusion(Request $request) {

        // проверяем и записываем в бд данные Заключения
        $conclusion = new ServiceConclusion;
        $conclusion->order_id = intval($request->order_id);
        $conclusion->requisites = trim(strip_tags(htmlspecialchars($request->service_requisites)));
        $conclusion->document_date = trim(strip_tags(htmlspecialchars($request->document_date)));
        $conclusion->item_name = trim(strip_tags(htmlspecialchars($request->item_name)));
        $conclusion->item_sn = trim(strip_tags(htmlspecialchars($request->item_sn)));
        $conclusion->item_sale_date = trim(strip_tags(htmlspecialchars($request->item_sale_date)));
        $conclusion->item_receiving_date = trim(strip_tags(htmlspecialchars($request->item_repare_receiving)));
        $conclusion->client_name = trim(strip_tags(htmlspecialchars($request->client_name)));
        $conclusion->client_phone = trim(strip_tags(htmlspecialchars($request->client_phone)));
        $conclusion->guarantee_availability = intval($request->guarantee_availability);
        $conclusion->seller_record = intval($request->seller_record);
        $conclusion->external_damage = trim(strip_tags(htmlspecialchars($request->external_damage)));
        $conclusion->client_damage_detected = trim(strip_tags(htmlspecialchars($request->client_damage_detected)));
        $conclusion->diagnostic = trim(strip_tags(htmlspecialchars($request->item_diagnostic)));
        $conclusion->damage_reason = trim(strip_tags(htmlspecialchars($request->item_damage_reason)));
        $conclusion->recovery_recommendation = trim(strip_tags(htmlspecialchars($request->item_recovery_recommendation)));
        $conclusion->signature_name = trim(strip_tags(htmlspecialchars($request->signature_name)));
        $conclusion->save();

        // записываем отметку в ордере о том что создано Заключение
        $order = Order::where('id', intval($request->order_id))->first(['id', 'conclusion']);
        $order->conclusion = 1;
        $order->update();

        $data = $this->getUserData($request);
        $profile = $request->user()->profile()->with('address', 'contact', 'repairs', 'orders.items')->first();
        $data['orders'] = $profile->orders()->with('items')->orderBy('id', 'desc')->paginate(10);
        $data['order_mark'] = intval($request->order_id);

        return view('service.cabinet_orders', $data);
    }

    public function editConclusion(Request $request) {

        $data = $this->getUserData($request);
        $data['conclusion'] = ServiceConclusion::where('order_id', $request->order_id)->first();

        // если запрос от role=8 (бухгалтер)
        if($request->buh) {
            $data['buh'] = 1;
            return view('service.includes.conclusion_edit_form', $data);
        } else {
            return view('service.edit_conclusion', $data);
        }

    }

    public function updateConclusion(Request $request) {

        // проверяем и обновляем данные Заключения
        ServiceConclusion::where('order_id', intval($request->order_id))->update(
            [
                'requisites' => trim(strip_tags(htmlspecialchars($request->service_requisites))),
                'document_date' => trim(strip_tags(htmlspecialchars($request->document_date))),
                'item_name' => trim(strip_tags(htmlspecialchars($request->item_name))),
                'item_sn' => trim(strip_tags(htmlspecialchars($request->item_sn))),
                'item_sale_date' => trim(strip_tags(htmlspecialchars($request->item_sale_date))),
                'item_receiving_date' => trim(strip_tags(htmlspecialchars($request->item_repare_receiving))),
                'client_name' => trim(strip_tags(htmlspecialchars($request->client_name))),
                'client_phone' => trim(strip_tags(htmlspecialchars($request->client_phone))),
                'guarantee_availability' => intval($request->guarantee_availability),
                'seller_record' => intval($request->seller_record),
                'external_damage' => trim(strip_tags(htmlspecialchars($request->external_damage))),
                'client_damage_detected' => trim(strip_tags(htmlspecialchars($request->client_damage_detected))),
                'diagnostic' => trim(strip_tags(htmlspecialchars($request->item_diagnostic))),
                'damage_reason' => trim(strip_tags(htmlspecialchars($request->item_damage_reason))),
                'recovery_recommendation' => trim(strip_tags(htmlspecialchars($request->item_recovery_recommendation))),
                'signature_name' => trim(strip_tags(htmlspecialchars($request->signature_name))),
            ]
        );

        $data = $this->getUserData($request);
        $profile = $request->user()->profile()->with('address', 'contact', 'repairs', 'orders.items')->first();
        $data['orders'] = $profile->orders()->with('items')->orderBy('id', 'desc')->paginate(10);
        $data['order_mark'] = intval($request->order_id);

        // если обновляет бухгалтер
        if($request->buh) {
            $order_id_buh = $request->order_id;
            return redirect('svetik')->with('order_id_buh', $order_id_buh);
        } else {
            return view('service.cabinet_orders', $data);
        }

    }

    public function downloadConclusion(Request $request) {

        $conclusion = ServiceConclusion::where('order_id', intval($request->order_id))->first();

        $pdf = PDF::loadView("service.includes.conclusion", compact('conclusion'));


        return $pdf->download("zaklucheniye_".$conclusion->order_id.".pdf");

        // return view('service.includes.conclusion', compact('conclusion'));
    }

    public function makeAct(Request $request) {

        $data = $this->getUserData($request);
        $data['order'] = Order::where('id', $request->order_id)->first();

        return view('service.make_act', $data);
    }

    public function saveAct(Request $request) {

        // проверяем и записываем в бд данные Акта
        $act = new ServiceAct;
        $act->order_id = intval($request->order_id);
        $act->act_date = trim(strip_tags(htmlspecialchars($request->act_date)));
        $act->client_name = trim(strip_tags(htmlspecialchars($request->client_name)));
        $act->company_name = trim(strip_tags(htmlspecialchars($request->company_name)));
        $act->client_requisites = trim(strip_tags(htmlspecialchars($request->client_requisites)));
        $act->company_requisites = trim(strip_tags(htmlspecialchars($request->company_requisites)));
        $act->signature_client_name = trim(strip_tags(htmlspecialchars($request->signature_client_name)));
        $act->signature_director_name = trim(strip_tags(htmlspecialchars($request->signature_director_name)));
        $act->save();

        // записываем отметку в ордере о том что создан Акт
        $order = Order::where('id', intval($request->order_id))->first(['id', 'act']);
        $order->act = 1;
        $order->update();

        $data = $this->getUserData($request);
        $profile = $request->user()->profile()->with('address', 'contact', 'repairs', 'orders.items')->first();
        $data['orders'] = $profile->orders()->with('items')->orderBy('id', 'desc')->paginate(10);
        $data['order_mark'] = intval($request->order_id);

        return view('service.cabinet_orders', $data);
    }

    public function editAct(Request $request) {

        $data = $this->getUserData($request);
        $data['act'] = ServiceAct::where('order_id', $request->order_id)->first();
        $data['order'] = Order::find($request->order_id);

        return view('service.edit_act', $data);
    }

    public function updateAct(Request $request) {

        // проверяем и обновляем данные Заключения
        ServiceAct::where('order_id', intval($request->order_id))->update(
            [
                'act_date' => trim(strip_tags(htmlspecialchars($request->act_date))),
                'client_name' => trim(strip_tags(htmlspecialchars($request->client_name))),
                'company_name' => trim(strip_tags(htmlspecialchars($request->company_name))),
                'client_requisites' => trim(strip_tags(htmlspecialchars($request->client_requisites))),
                'company_requisites' => trim(strip_tags(htmlspecialchars($request->company_requisites))),
                'signature_client_name' => trim(strip_tags(htmlspecialchars($request->signature_client_name))),
                'signature_director_name' => trim(strip_tags(htmlspecialchars($request->signature_director_name))),
            ]
        );

        $data = $this->getUserData($request);
        $profile = $request->user()->profile()->with('address', 'contact', 'repairs', 'orders.items')->first();
        $data['orders'] = $profile->orders()->with('items')->orderBy('id', 'desc')->paginate(10);
        $data['order_mark'] = intval($request->order_id);

        return view('service.cabinet_orders', $data);
    }

    public function downloadAct(Request $request) {

        $act = ServiceAct::where('order_id', intval($request->order_id))->first();
        $order = Order::find($request->order_id);

        $pdf = PDF::loadView("service.includes.act", compact('act', 'order'));


        return $pdf->download("act_".$act->order_id.".pdf");

        // return view('service.includes.act', compact('act', 'order'));
    }

    // для поиска и печати заключений для Светланы
    public function сonclusionPrint(Request $request)
    {
        $data = $this->getUserData($request);

        return view('conclusion-print')->with($data);
    }

    public function conclusionSearch(Request $request) // ajax
    {

        // смотрим есть ли заключение
        $conclusion = ServiceConclusion::where('order_id', $request->сonclusion_num)->first();

        // если не найдено
        if(!$conclusion) {

            // возвращаем пустоту
            return "";

        } else {

            // возвращаем сохраненное заключение
            return view('service.includes.conclusion', compact('conclusion'));

        }
    }

}
