@extends('layouts.service')

@section('content')

@include('service.includes.header')
@include('service.includes.nav')

<div class="make-conclusion">
    <div class="container">

        <div class="make-conclusion_title">
            Откорректируйте данные и сохраните:
        </div>

        <form method="post" action="{{ asset('service/conclusion/save') }}">

            <div class="make-conclusion_document-block">

                {{ csrf_field() }}

                @php
                    if($generalProfile->contact->count()) {
                        $phone = $generalProfile->contact[0]->phone;
                    } else {
                        $phone = "";
                    }
                @endphp

                <div class="service-requisites">
                    <textarea name="service_requisites" required>{{ 
                        $generalProfile->company_name."\n"
                        .$generalProfile->company_address."\n"
                        .$phone 
                    }}</textarea>
                </div>

                <div class="document-header">
                    ЗАКЛЮЧЕНИЕ №{{ $order->id }}
                </div>

                <div class="info-block-1">

                    <div class="line">
                        <div>От:</div>
                        <div>
                            <input type="date" name="document_date" value="{{ date("Y-m-d") }}" required>
                        </div>
                    </div>

                    <div class="line">
                        <div>Изделие:</div>
                        <div>
                            <textarea name="item_name" required>{{ $order->item_name }}</textarea>
                        </div>
                    </div>

                    <div class="line">
                        <div>Серийный номер:</div>
                        <div>
                            <input type="text" name="item_sn" value="{{ $order->item_sn }}" required>
                        </div>
                    </div>

                    <div class="line">
                        <div>Дата продажи:</div>
                        <div>
                            <input type="date" name="item_sale_date" value="{{ $order->item_sale_date }}" required>
                        </div>
                    </div>

                    <div class="line">
                        <div>Дата поступления изделия в ремонт:</div>
                        <div>
                            <input type="date" name="item_repare_receiving" value="" required>
                        </div>
                    </div>

                    <div class="line">
                        <div>Фамилия и инициалы потребителя:</div>
                        <div>
                            <input type="text" name="client_name" value="{{ $order->client_name }}" required>
                        </div>
                    </div>

                    <div class="line">
                        <div>Телефон:</div>
                        <div>
                            <input type="text" name="client_phone" value="{{ $order->client_phone }}" required>
                        </div>
                    </div>

                    <div class="line">
                        <div>Наличие гарантийного талона:</div>
                        <div>
                            Есть - <input type="radio" name="guarantee_availability" value=1 required>, 
                            Нет - <input type="radio" name="guarantee_availability" value=0 required>
                        </div>
                    </div>

                    <div class="line">
                        <div>Отметка продавца в гарантийном талоне:</div>
                        <div>
                            Есть - <input type="radio" name="seller_record" value=1 required>, 
                            Нет - <input type="radio" name="seller_record" value=0 required>
                        </div>
                    </div>

                    <div class="line">
                        <div>Наличие внешних мех.повреждений:</div>
                        <div>
                            <textarea name="external_damage" required></textarea>
                        </div>
                    </div>

                </div>

                <div class="info-block-2">

                    <div class="block-2-element">
                        <div class="header">Выявленные потребителем повреждения, неисправности:</div>
                        <div>
                            <textarea name="client_damage_detected" required>{{ $order->item_defect }}</textarea>
                        </div>
                    </div>

                    <div class="block-2-element">
                        <div class="header">Результат осмотра:</div>
                        <div>
                            <textarea name="item_diagnostic" required>{{ $order->item_diagnostic }}</textarea>
                        </div>
                    </div>

                    <div class="block-2-element">
                        <div class="header">Предполагаемая причина повреждения, неисправности:</div>
                        <div>
                            <textarea name="item_damage_reason" required></textarea>
                        </div>
                    </div>

                    <div class="block-2-element">
                        <div class="header">Предложения по восстановлению работоспособности, внешнего вида или замене изделия:</div>
                        <div>
                            <textarea name="item_recovery_recommendation" required></textarea>
                        </div>
                    </div>

                </div>

                <div class="signature-block">
                    <div class="signature-wrapper">
                        <div>
                            Директор ______________________
                            <input type="text" name="signature_name" value="" placeholder="ИП Иванов А.А." required>
                        </div>                        
                    </div>
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