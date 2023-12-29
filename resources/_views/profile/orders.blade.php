@extends('layouts.new_app')

@section('content')

    <div class="page">

        <div class="container">

            <div class="cabinet-page">

                @include('profile.menu')

                <h1>История заказов</h1>

                <div class="history-block">

                    @foreach($orders as $order)

                        <div x-data="{show: false}" class="">

                            <div class="history-page_order-header-line _js-order-block">
                                <div class="order-number js-items-block-button" title="Развернуть">
                                    Заказ №{{ $order->id }}
                                </div>
                                <div class="order-date">
                                    Создан {{ date('d.m.Y', strtotime($order->created_at)) }}
                                </div>
                                <div class="order-price">
                                    товаров <span class="color">{{ $order->items->count() }}</span>,
                                    на сумму <span class="color">{{ price($order->price) }}</span> руб.
                                </div>
                                <div class="arrow-block _js-items-block-button">
                                    <div @click="show=!show" class="svg js-svg" title="Развернуть">
                                        @include('svg.arrow')
                                    </div>
                                </div>
                            </div>

                            <div x-show="show" x-cloak x-transition class="history-page_items-block-wrapper js-items-block">

                                <div class="history-page_items-block">

                                    <div class="items-header">
                                        <div class="code">
                                            Код
                                        </div>
                                        <div class="name">
                                            Наименование
                                        </div>
                                        <div class="item-price">
                                            Цена
                                        </div>
                                        <div class="item-count">
                                            Кол-во
                                        </div>
                                        <div class="item-line-price">
                                            Стоимость
                                        </div>
                                    </div>

                                    @foreach($order->items as $item)

                                        <div class="item-lines">
                                            <div class="code">
                                                {{ $item->item_1c_id }}
                                            </div>
                                            <div class="name">
                                                <a href="{{ route('item.index', $item->item_1c_id) }}" target="_blank">
                                                    {{ $item->item_name }}
                                                </a>
                                            </div>
                                            <div class="item-price">
                                                {{ price($item->item_price) }}
                                            </div>
                                            <div class="item-count">
                                                {{ $item->item_count }}
                                            </div>
                                            <div class="item-line-price">
                                                {{ price($item->item_sum_price) }}
                                            </div>
                                        </div>

                                    @endforeach

                                </div>
                            </div>

                        </div>

                    @endforeach

                </div>

                @include('pagination.default', ['paginator' => $orders])

            </div>

        </div>

    </div>

@endsection

@section('scripts')
    @parent

    <script type="text/javascript" src="{{ asset('assets/js/new_profile.js') }}"></script>

@endsection
