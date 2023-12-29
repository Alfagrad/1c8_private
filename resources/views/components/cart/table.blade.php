@props(['orderItems', 'title' => ''])

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
                    Цена
                </div>

                <div class="purcent js-cart-head">
                    Скидка / Надбавка
                </div>

                <div class="calculated-price js-cart-head">
                    Цена расчетная
                </div>

                <div class="count js-cart-head">
                    Кол-во
                </div>

                <div class="weight js-cart-head">
                    Вес
                </div>

                <div class="total js-cart-head">
                    Итого
                </div>

                <div class="end"></div>

            </div>

        </div>

        <div class="items-line-block">

            @foreach($orderItems as $key => $orderItem)

                @php
                    $item = $orderItem->item;
                    $isLessMin = $orderItem->item_sum_price < config('settings.min_cart_to_order');
                @endphp

                @include('item.line.cart', ["class" => "cart-item-line"])
{{--                @include('item.line.cart', ["style" => "cart-item-line ".$orderItem->item->amount <= 0 || $isLessMin? 'bg-error-light' : ''])--}}

{{--                <x-item.line :$item class="cart-item-line {{$orderItem->item->amount <= 0 || $isLessMin? 'bg-error-light' : '' }}">--}}

{{--                    <x-slot:move>--}}
{{--                        <input wire:model="cartsSelected" value="{{$key}}" type="checkbox" title="Отметить для копирования/переноса/удаления"/>--}}
{{--                    </x-slot:move>--}}

{{--                    <x-slot:checkAmount>--}}
{{--                        @if($orderItem->item->amount <= 0)--}}
{{--                            <div style='color: red; font-weight: bold;'>! Уточните наличие этой позиции у Вашего менеджера !</div>--}}
{{--                        @endif--}}
{{--                    </x-slot:checkAmount>--}}

{{--                    <x-slot:calcPrice>--}}
{{--                        <x-item.opt_price :$item></x-item.opt_price>--}}
{{--                        <div class="purcent">--}}
{{--                            <div>--}}
{{--                                <div class="catalog-item-line_mobile-price-title">--}}
{{--                                    скидка--}}
{{--                                </div>--}}
{{--                                <span class="">{{ $orderItem->discount ?? 0 }}</span>%--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="calculated-price">--}}
{{--                            <div>--}}
{{--                                <div class="catalog-item-line_mobile-price-title">--}}
{{--                                    цена расч--}}
{{--                                </div>--}}
{{--                                <div class="">{{ price($orderItem->item_price) }}</div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </x-slot:calcPrice>--}}

{{--                    <x-slot:count>--}}
{{--                        <div class="catalog-item-line_input-block-wrapper">--}}
{{--                            <livewire:count :cart="$orderItem->cart" :key="$orderItem->cart->id"/>--}}
{{--                        </div>--}}
{{--                    </x-slot:count>--}}

{{--                    <x-slot:weight>--}}
{{--                        <x-item.weight :$item :count="$orderItem->item_count"></x-item.weight>--}}
{{--                    </x-slot:weight>--}}

{{--                    <x-slot:sum>--}}
{{--                        <div class="total-price">--}}
{{--                            <div>--}}
{{--                                <div class="catalog-item-line_mobile-price-title">--}}
{{--                                    итого--}}
{{--                                </div>--}}
{{--                                <div class="">--}}
{{--                                    {{price($orderItem->item_sum_price)}}--}}
{{--                                    @if($isLessMin)--}}
{{--                                        <div class="text-error-dark text-xs">Сумма менее {{price(config('settings.min_cart_to_order'))}} руб.</div>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </x-slot:sum>--}}

{{--                    <x-slot:delete>--}}
{{--                        <div wire:click="delete('{{$orderItem->cart->id}}')" class="del-item">--}}
{{--                            <span class="">+</span>--}}
{{--                        </div>--}}
{{--                    </x-slot:delete>--}}

{{--                </x-item.line>--}}

            @endforeach

        </div>

    </div>

@endif
