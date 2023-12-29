@extends('layouts.service')

@section('content')

@include('service.includes.header')
@include('service.includes.nav')

<div class="make-act">
    <div class="container">

        <div class="make-act_title">
            Откорректируйте данные и сохраните:
        </div>

        <form method="post" action="{{ asset('service/act/update') }}">

            <div class="make-act_document-block">

                {{ csrf_field() }}

                <div class="document-header">
                    АКТ ВЫПОЛНЕННЫХ РАБОТ №{{ $act->id }} от 
                    <input type="date" name="act_date" value="{{ $act->act_date }}" required class="inline-input" style="width: 140px; font-weight: bold;">
                </div>

                <div class="document-description">
                    Мы, нижеподписавшиеся, Заказчик 
                    <input type="text" name="client_name" value="{{ $act->client_name }}" required class="inline-input" style="width: 661px;">
                    и Исполнитель
                    <input type="text" name="company_name" value="{{ $act->company_name }}" required class="inline-input" style="width: 795px;">
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
                                <textarea name="client_requisites" required>{{ $act->client_requisites }}</textarea>
                            </td>
                            <td>
                                <textarea name="company_requisites" required>{{ $act->company_requisites }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                ________________
                                <input type="text" name="signature_client_name" value="{{ $act->signature_client_name }}" required class="inline-input" style="width: 230px;">
                            </td>
                            <td>
                                ________________
                                <input type="text" name="signature_director_name" value="{{ $act->signature_director_name }}" required class="inline-input" style="width: 230px;">
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