@extends('layouts.app')

@section('content')
<body>
<div class="b-wrapper p-cabinet orders-history">
    @include('general.header')
    @include('general.nav')
    @include('general.bcrumb')
    <section class="s-main-wrapper">
        <div class="container">
            <div class="wrapper">
                <div class="w-main-table">
                    @include('profile.menu')
                    <div class="right">
                        <div class="wrapper white-bg-wrapper">
                            <div class="wrapper b-cabinet-content">
                                <div class="wrapper w-orders-history">
                                    <div class="section-name">История заказов</div>
                                    <div class="wrapper items-table orders-table">
                                        @foreach($orders as $order)
                                            <div class="w-table">
                                            <div class="table-head">
                                                <table>
                                                    <thead>
                                                    <tr>
                                                        <td class="td-toggler"><div class="toggler-button"></div></td>
                                                        <td class="td-article"><div class="mobile-helper">Номер:</div>{{$order->id}}</td>
                                                        <td class="td-order-status">Оформлен {{$order->created_at->format('d.m.Y')}}</td>
                                                        @if($order->delivery == 'Доставка')
                                                            <td class="td-delivery-type">{{$order->delivery}}, {{$order->address}}</td>
                                                            @else
                                                            <td class="td-delivery-type">{{$order->delivery}}</td>
                                                        @endif
                                                        <td class="td-pay-status">{{$order->calculation}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="td-toggler"></td>
                                                        <td colspan="2" class="td-order-weight">Ориентировочный вес: <b>{{$order->weight}} кг</b></td>
                                                        <td class="td-sale-info">Общая стоимость заказа:<br>
                                                        @if($order->general_discount)
                                                            Общая скидка/надбавка: {{$order->general_discount}}%
                                                        @endif
                                                        </td>
                                                        <td class="td-order-price">{{$order->price}}</td>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <div class="table-body" style="display: none;">
                                                <table>
                                                    <tbody>
                                                    <tr class="orders-history-head">
                                                        <td class="td-article"></td>
                                                        <td class="td-name">Наименование</td>
                                                        <td class="td-price">Цена</td>
                                                        <td class="td-pcs">Кол-во</td>
                                                        <td class="td-price">Итого</td>
                                                    </tr>
                                                    @foreach($order->items as $item)
                                                        <tr class="orders-history-item">
                                                            <td class="td-article"><div class="mobile-helper">Код: </div>{{$item->id}}</td>
                                                            <td class="td-name"><a href="" class="name">{{$item->item_name}}</a></td>
                                                            <td class="td-price">{{$item->item_price}} руб.</td>
                                                            <td class="td-pcs"><div class="mobile-helper">Кол-во: </div>{{$item->item_count}}</td>
                                                            <td class="td-price">{{$item->item_sum_price}} руб.</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="wrapper pagination">
                                        @include('pagination.default', ['paginator' => $orders])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('general.footer')
    @include('general.popups')
</div>
@include('general.scripts')
</body>
@endsection