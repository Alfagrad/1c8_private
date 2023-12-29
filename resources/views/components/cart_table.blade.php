@props(['carts', 'title' => '', 'currentAgreement' => null, 'discounts', 'prices', 'sums', 'minCartToOrder'])

@if($carts->count() > 0)

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

            @foreach($carts as $cart)

{{--                <livewire:cart :$cart :key="$cart->id"/>--}}

                @php
                    $item = $cart->item;
                    $sum = $sums->get($cart->id);
                    $isLessMin = $sum < $minCartToOrder;
                @endphp

                <x-item.line :$item class="cart-item-line {{$cart->item->amount <= 0 || $isLessMin? 'bg-error-light' : '' }}">

                    <x-slot:move>
                        <input wire:model="cartsSelected" value="{{$cart->id}}" type="checkbox" title="Отметить для копирования/переноса"/>
{{--                        <div class="checkbox-block">--}}
{{--                            <div class="checker-wrapper">--}}
{{--                                <div class="checker js-item-checker" title="Отметить для копирования/переноса">--}}
{{--                                    <span>&#10004;</span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </x-slot:move>

                    <x-slot:checkAmount>
                        @if($cart->item->amount <= 0)
                            <div style='color: red; font-weight: bold;'>! Уточните наличие этой позиции у Вашего менеджера !</div>
                        @endif
                    </x-slot:checkAmount>

                    <x-slot:calcPrice>
                        <div class="purcent">
                            <div>
                                <div class="catalog-item-line_mobile-price-title">
                                    скидка
                                </div>
                                <span class="">{{ $discounts->get($cart->id) }}</span>%
                            </div>
                        </div>

                        <div class="calculated-price">
                            <div>
                                <div class="catalog-item-line_mobile-price-title">
                                    цена расч
                                </div>
                                <div class="">{{ price($prices->get($cart->id)) }}</div>
                            </div>
                        </div>
                    </x-slot:calcPrice>

                    <x-slot:count>
                        <div class="catalog-item-line_input-block-wrapper">
                            <livewire:count :$cart :$item :key="$cart->id"/>
                        </div>
                    </x-slot:count>

                    <x-slot:weight>
                        <x-item.weight :$item :count="$cart->count"></x-item.weight>
                    </x-slot:weight>

                    <x-slot:sum>
                        <div class="total-price">
                            <div>
                                <div class="catalog-item-line_mobile-price-title">
                                    итого
                                </div>
{{--                                <div class="js-line-total-price">--}}
                                <div class="">
                                    {{price($sum)}}
                                    @if($isLessMin)
                                        <div class="text-error-dark text-xs">Сумма менее {{price($minCartToOrder)}}руб.</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </x-slot:sum>

                    <x-slot:delete>
                        <div wire:click="delete('{{$cart->id}}')" class="del-item">
                            <span class="">+</span>
                        </div>
                    </x-slot:delete>

                </x-item.line>

{{--                @include('catalog.snippets.new_item_line', [--}}
{{--//                        'count_in_cur_cart' => $item->count,--}}
{{--                        'cart' => $cart--}}
{{--                    ])--}}

            @endforeach

        </div>

    </div>

@endif
