@extends('cart.table.table')

@if($orderItems->count() > 0)

    <div class="cart-items-block">

        <div class="cart-items-title">{{$title}}</div>

        <div class="cart-header-wrapper">

            <div class="cart-header-block">

                <div class="checker-wrapper">

                    <div class="checker js-all-items-checker" title="Отметить для копирования/переноса">
                        <span>&#10004;</span>
                    </div>

                </div>

                <div class="code">
                    Код
                </div>

                <div class="name">
                    Наименование
                </div>

                <div class="price js-cart-head">
                    {{--                    Цена--}}
                </div>

                <div class="purcent js-cart-head">
                    {{--                    Скидка / Надбавка--}}
                </div>

                <div class="calculated-price js-cart-head">
                    {{--                    Цена расчетная--}}
                </div>

                <div class="count js-cart-head">
                    Кол-во
                </div>

                <div class="weight js-cart-head">
                    Вес
                </div>

                <div class="end"></div>

            </div>

        </div>

        <div class="items-line-block">

            @foreach($orderItems as $orderItem)

                @php
                    $item = $orderItem->item;
                    $cart = $orderItem->cart;
                @endphp

                @include('item.line.cart-service', ["class" => "cart-item-line"])

            @endforeach

        </div>

    </div>

@endif
