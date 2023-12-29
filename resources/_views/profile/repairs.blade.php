@extends('layouts.new_app')

@section('content')

<div class="page">

    <div class="container">

        <div class="cabinet-page">

            @include('profile.menu')

            <div class="section-name">
                Мои ремонты:
                <input
                    type="text"
                    class="thin"
                    id="js-repair-search-input"
                    placeholder="Начните вводить номер"
                >
            </div>

            <div class="cabinet_repairs">

                @if($repairs->count())

                    <div class="title-block">
                        <div class="receipt-num">Квитанция</div>
                        <div class="item-name">Изделие</div>
                        <div class="repair-status">Статус</div>
                        <div class="arrow">Подробнее</div>
                    </div>

                    <div class="repair-block js-repair-block">

                        @foreach($repairs as $repair)

                            <div x-data="{show: false}" class="repair-wrapper _js-repair-wrapper">

                                <div class="repair-line">

                                    <div class="receipt-num js-receipt-num">{{ $repair->{'1c_id'} }}</div>

                                    <div class="item-name">{{ $repair->name }}</div>

                                    @php
                                        $st = $repair->state;
                                        $ret = $repair->return_item;
                                        if($st == 1 && $ret == 0) {
                                            $state = "В работе.";
                                        } elseif($st == 2 && $ret == 0) {
                                            if($repair->shipment_date != '0000-00-00') {
                                                // прибавляем 1 день к дате
                                                // задача - https://alfastok.bitrix24.by/company/personal/user/270/tasks/task/view/71064/
                                                $shipment_date = date('d.m.Y', strtotime($repair->shipment_date.'+ 1 days'));
                                                $state = "Отремонтирован. Ожидаемая дата доставки в пункт выдачи {$shipment_date}";
                                            } else {
                                                $state = "Отремонтирован и готов к выдаче";
                                            }
                                        } elseif($st == 3) {
                                            $state = "Выдан";
                                            if($repair->check_date != '0000-00-00') {
                                                $check_date = date('d.m.Y', strtotime($repair->check_date));
                                                $state .= " {$check_date}";
                                            }
                                            $state .= ".";
                                        } elseif($ret == 1) {
                                            $state = "Возврат денежных средств.";
                                        } elseif($ret == 2) {
                                            $state = "Возврат товара: замена на новое изделие.";
                                        } else {
                                            $state = "Не определен.";
                                        }
                                    @endphp

                                    <div class="repair-status">
                                        <div>{{ $state }}</div>
                                    </div>

                                    <div @click="show=!show" class="arrow js-repair-arrow">
                                        @include('svg.phones_arrow_ico')
                                    </div>

                                </div>

                                <div x-show="show" x-cloak x-transition class="drop-down-block _js-drop-down-block">

                                    @if($repair->serial)

                                        <div class="info-line">
                                            <div class="title">Серийный номер:</div>
                                            <div class="info">{{ $repair->serial }}</div>
                                        </div>

                                    @endif

                                    @php
                                        if($repair->repair_type == 0) {
                                            $repair_type = "Платный.";
                                        } elseif($repair->repair_type == 1) {
                                            $repair_type = "Бесплатный.";
                                        } else {
                                            $repair_type = "";
                                        }
                                    @endphp

                                    @if($repair_type && $repair->state != 1)

                                        <div class="info-line">
                                            <div class="title">Вид ремонта:</div>
                                            <div class="info">{{ $repair_type }}</div>
                                        </div>

                                    @endif

                                    @if($repair->repair_sum != 0)

                                        @php
                                            if($repair->paid == 1) $paid = "оплачено";
                                                else $paid = "к оплате";
                                        @endphp

                                        <div class="info-line">
                                            <div class="title">Стоимость ремонта:</div>
                                            <div class="info">{{ number_format($repair->repair_sum, 2, '.', '') }} руб. ({{ $paid }})</div>
                                        </div>

                                    @endif

                                    @if($repair->comment)

                                        <div class="info-line">
                                            <div class="title">Коментарий:</div>
                                            <div class="info">{{ $repair->comment }}</div>
                                        </div>

                                    @endif

                                    @if($repair->works)

                                        <div class="info-line">
                                            <div class="title">Материалы и выполненные работы:</div>
                                            <div class="info"><pre>{{ $repair->works }}</pre></div>
                                        </div>

                                    @endif

                                </divx-show>

                            </div>


                        @endforeach

                    </div>

                @else

                    <div>Ремонты не найдены</div>

                @endif

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent

<script type="text/javascript" src="{{ asset('assets/js/new_profile.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/repair.js') }}"></script>


@endsection
