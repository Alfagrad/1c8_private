@extends('layouts.service')

@section('content')

@include('service.includes.header')
@include('service.includes.nav')

<div class="make-act">
    <div class="container">

        <div class="make-act_title">
            Откорректируйте данные и сохраните:
        </div>

        <form method="post" action="{{ asset('service/act/save') }}">

            <div class="make-act_document-block">

                {{ csrf_field() }}

                <div class="document-header">
                    АКТ ВЫПОЛНЕННЫХ РАБОТ №{{ $order->id }} от 
                    <input type="date" name="act_date" value="{{ date('Y-m-d') }}" required class="inline-input" style="width: 140px; font-weight: bold;">
                </div>

                <div class="document-description">
                    Мы, нижеподписавшиеся, Заказчик 
                    <input type="text" name="client_name" value="{{ $order->client_name }}" required class="inline-input" style="width: 661px;">
                    и Исполнитель
                    <input type="text" name="company_name" value="{{ $generalProfile->company_name }} в лице директора ..." required class="inline-input" style="width: 795px;">
                    составили настоящий Акт о нижеследующем: в соответствии с Договором Исполнитель сдал, а заказчик принял следующие работы:
                </div>

                <div class="document-work-list">
                    <table>
                        <tr>
                            <th class="num">№ п/п</th>
                            <th class="name">Наименование работы, услуги, материала, прочих затрат</th>
                            <th class="unit">Ед. изм</th>
                            <th class="pcs">Кол-во</th>
                        </tr>

                        @php($i = 1)
                        @foreach($order->items as $item)

                            <tr>
                                <td class="num">{{ $i }}</td>
                                <td class="name">{{ $item->item_name }}</td>
                                <td class="unit">шт.</td>
                                <td class="pcs">{{ $item->item_count }}</td>
                            </tr>

                            @php ($i++)

                        @endforeach

                    </table>
                </div>

                <div class="document-body">
                    Работы выполнены в полном объеме. Качество работы соответствует указанным в Договоре требованиям. Недостатки в результате приема работ не выявлены. Заказчик к выполненной работе претензий не имеет.
                </div>

                <div class="document-requisites">
                    <table>
                        <tr>
                            <th>Заказчик:</th>
                            <th>Исполнитель:</th>
                        </tr>
                        <tr>
                            <td>
                                <textarea name="client_requisites" required>{{
                                    $order->client_name
                                    ."\n".$order->client_phone 
                                }}</textarea>
                            </td>
                            <td>

                                @php
                                    if($generalProfile->contact->count()) {
                                        $phone_str = $generalProfile->contact[0]->phone.", ".$generalProfile->contact[0]->name;
                                    } else {
                                        $phone_str = "";
                                    }
                                @endphp

                                <textarea name="company_requisites" required>{{
                                    $generalProfile->company_name
                                    ."\nУНП: ".$generalProfile->unp
                                    ."\np/c: ".$generalProfile->bank_account
                                    ."\nв: "
                                    ."\nкод банка: ".$generalProfile->bank_name
                                    ."\nадрес: ".$generalProfile->company_address
                                    ."\nтелефон: ".$phone_str
                                }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                ________________
                                <input type="text" name="signature_client_name" value="{{ $order->client_name }}" required class="inline-input" style="width: 230px;">
                            </td>
                            <td>
                                ________________
                                <input type="text" name="signature_director_name" value="" required class="inline-input" style="width: 230px;">
                            </td>
                        </tr>
                    </table>

                </div>

            </div>

            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div class="submit-block">
                <button type="submit">Сохранить</button>
            </div>

        </form>
       
    </div>
</div>



@endsection