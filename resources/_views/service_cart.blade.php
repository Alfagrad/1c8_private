@extends('layouts.new_app')

@section('content')

<div class="cart-page">
    <div class="container">

        <div class="breadcrumbs">

            <a href="{{ asset('home') }}" title="Переход на Главную страницу">
                Главная
            </a> 

            <span>|</span>

            <strong>Ремонты</strong>

        </div>
{{-- 

        <div class="cart-title">
            Корзина <strong>&laquo;<span class="js-cart-name">Основная</span>&raquo;</strong>:
            <strong><span class="js-count-items">{{ $carts->first()['count_items'] }}</span></strong> позиций,
            <strong><span class="js-count-all-items">{{ $carts->first()['count_all_items'] }}</span></strong> единиц.
        </div>

        <div class="cart-buttons">
            
            <div class="cart-buttons-block">
                <div class="title">Корзины: </div>

                @foreach($carts as $cart)

                    <div
                        class="cart-button js-cart-button @if($cart['cart_id'] == $cart_id){{ 'active' }}@endif"
                        data-cart-id="{{ $cart['cart_id'] }}"
                        data-cart-name="{{ $cart['name'] }}"
                        data-count-items="{{ $cart['count_items'] }}"
                        data-count-all-items="{{ $cart['count_all_items'] }}"
                    >
                        {{ $cart['name'] }}
                    </div>

                @endforeach

                <div class="cart-button add js-add-new-cart">Создать корзину</div>

                <div class="cart-button del js-del-cart"
                    data-cart-del-id="0"
                    style="display: @if($cart_id == 0){{ 'none;' }}@endif"
                >
                    Удалить корзину
                </div>
                
            </div>
        </div>

        <form method="post" action="{{ asset('/cart-page/order-create') }}" class="js-order-form">

            {{ csrf_field() }}
            <input type="hidden" name="cart_id" value="0">

            <div class="carts-block">

                @foreach($carts as $cart)

                    @if($cart['items']->count())

                        @php
                            if($cart['cart_id'] == $cart_id) {
                                $cart_style = "block";
                            } else {
                                $cart_style = "none";
                            }
                        @endphp

                        <div 
                            class="carts-element js-cart js-cart-id-{{ $cart['cart_id'] }}" 
                            style="display: {{ $cart_style }};"
                            data-cart-id="{{ $cart['cart_id'] }}"
                        >

                            @if($cart['items']->where('is_component', 0)->count())

                                <div class="cart-items-block">

                                    <div class="cart-items-title">Товары</div>

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

                                        @foreach($cart['items']->where('is_component', 0) as $item)

                                            @include('catalog.snippets.new_item_line', [
                                                    'cart_item_line' => true,
                                                    'count_in_cur_cart' => $item->count_in_cart,
                                                ])
                                            
                                        @endforeach

                                    </div>

                                </div>

                            @endif

                            @if($cart['items']->where('is_component', 1)->count())

                                <div class="cart-items-block">

                                    <div class="cart-items-title">Запчасти</div>

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

                                        @foreach($cart['items']->where('is_component', 1) as $item)

                                            @include('catalog.snippets.new_item_line', [
                                                    'cart_item_line' => true,
                                                    'count_in_cur_cart' => $item->count_in_cart,
                                                ])
                                            
                                        @endforeach

                                    </div>

                                </div>

                            @endif

                            <div class="cart-total-line">

                                <div class="cart-manipulate-block js-drop-down-title">

                                    <div class="title-block">

                                        <div class="title">Переместить/копировать товары...</div>

                                        <div class="arrow">
                                            @include('svg.phones_arrow_ico')
                                        </div>

                                    </div>

                                    <div class="drop-down-block js-drop-down" style="display: none;">

                                        <div class="relocate-block">

                                            <div class="title">
                                                Перенести в:
                                            </div>

                                            <div class="relocate-links-block">
                                                
                                                @foreach($carts as $cart_link)

                                                    @if($cart_link['cart_id'] == $cart['cart_id'])
                                                        @continue
                                                    @endif

                                                    <div class="relocate-link js-relocate" data-cart-id="{{ $cart_link['cart_id'] }}">
                                                        {{ $cart_link['name'] }}
                                                    </div>

                                                @endforeach

                                            </div>

                                        </div>

                                        <div class="relocate-block">

                                            <div class="title">
                                                Копировать в:
                                            </div>

                                            <div class="relocate-links-block">
                                                
                                                @foreach($carts as $cart_link)

                                                    @if($cart_link['cart_id'] == $cart['cart_id'])
                                                        @continue
                                                    @endif

                                                    <div class="relocate-link js-copy" data-cart-id="{{ $cart_link['cart_id'] }}">
                                                        {{ $cart_link['name'] }}
                                                    </div>

                                                @endforeach

                                            </div>

                                        </div>

                                        <div class="relocate-block">

                                            <div class="relocate-links-block">
                                                
                                                <div class="relocate-link js-delete-items" data-cart-id="{{ $cart['cart_id'] }}">
                                                    Удалить выбранное из корзины
                                                </div>

                                            </div>

                                        </div>

                                        @if($cart['cart_id'] != 0)

                                            <div class="relocate-block">

                                                <div class="relocate-links-block">
                                                    
                                                    <div class="relocate-link js-swapping" data-cart-id="{{ $cart['cart_id'] }}">
                                                        Поменять местами с Основной корзиной
                                                    </div>

                                                </div>

                                            </div>

                                        @endif

                                    </div>

                                </div>

                                <div></div>

                                <div class="result-info-block">
                                    <div>Итого:</div>
                                    <div><span class="js-total-weight"></span> кг</div>
                                    <div><span class="js-total-price"></span> руб.</div>
                                </div>

                            </div>

                            <div class="cart-empty-block">
                                <div class="cart-empty-link js-empty-cart">
                                    Очистить корзину <strong>&laquo;{{ $cart['name'] }}&raquo;</strong>
                                </div>
                            </div>

                        </div>

                    @else

                        <div class="carts-element no-items js-cart js-cart-id-{{ $cart['cart_id'] }}" @if($cart['cart_id'] != $cart_id){!! "style='display: none;'" !!}@endif>
                            Корзина <strong>&laquo;{{ $cart['name'] }}&raquo;</strong> пуста
                        </div>

                    @endif


                @endforeach

            </div>

            <div class="cart-empty-block">
                <div class="cart-empty-link js-empty-all-carts">
                    Очистить <strong>ВСЕ</strong> корзины
                </div>
            </div>

            <div class="cart-order-completion-wrapper">

                <div class="cart-order-completion-block js-completion-block" data-cart-id="{{ $cart_id }}">

                    <div class="input-wrapper">
                        
                        <div class="input-block">

                            <div class="title">Выберите способ доставки:</div>

                            @foreach($deliveryTypes as $dType)

                                <div class="input-element">

                                    <label>
                                        <input
                                            type="radio"
                                            name="delivery_type"
                                            value="{{ $dType->id }}"
                                            data-delivery_discount="{{ $dType->action }}"
                                            required
                                            @if($dType->id == 1)
                                            class="js-pickup"
                                            @else
                                            class="js-no-pickup"
                                            @endif
                                        >
                                        {{ $dType->text }}
                                    </label>
                                    
                                </div>

                            @endforeach
                            
                        </div>

                        <div class="input-block">

                            <div class="title">Выберите адрес доставки:</div>

                            @if($delivery_addresses->count())

                                @foreach($delivery_addresses as $addr)

                                    <div class="input-element">

                                        <label>

                                            <input
                                                type="radio"
                                                name="delivery_address"
                                                value="{{ $addr['uuid'] }}"
                                                class="js-delivery-address"
                                                required
                                                disabled
                                                >
                                            {{ $addr['address'] }}

                                        </label>
                                        
                                    </div>

                                @endforeach

                            @endif

                        </div>

                        <div class="delivery-comment">

                            <div class="title">Дополнительный комментарий к заказу:</div>

                            <textarea name="comment_to_order"></textarea>

                        </div>

                    </div>

                    <div class="input-wrapper">

                        <div class="input-block">

                            <div class="title">Выберите соглашение:</div>

                            @if($common_agreements->count())

                                <div class="title">Типовое:</div>

                                @foreach($common_agreements as $agreement)

                                    <div class="input-element ">

                                        <label>

                                            <input
                                                class="js-paying-type"
                                                type="radio"
                                                name="calc_type"
                                                value="{{ $agreement->uuid }}"
                                                data-calc_discount="{{ $agreement->formula }}"
                                                data-agreement_items="{{ $agreement->items_str }}"
                                                required
                                            >

                                            {{ $agreement->name }}

                                            @if($agreement->formula)

                                                (
                                                @if($agreement->formula > 0){{ '+' }}@else{{ 'до ' }}@endif{{ $agreement->formula }}%
                                                )

                                            @endif

                                        </label>
                                        
                                    </div>

                                @endforeach

                            @endif

                            @if($personal_agreements->count())

                                <div class="title">Индивидуальное:</div>

                                @foreach($personal_agreements as $agreement)

                                    <div class="input-element js-personal-agreement">

                                        <label>

                                            <input
                                                class="js-paying-type"
                                                type="radio"
                                                name="calc_type"
                                                value="{{ $agreement->uuid }}"
                                                data-calc_discount="{{ $agreement->formula }}"
                                                data-partner_uuid="{{ $agreement->partner }}"
                                                data-agreement_items="{{ $agreement->items_str }}"
                                                required
                                                disabled
                                            >

                                            {{ $agreement->name }}

                                            @if($agreement->formula)

                                                (
                                                @if($agreement->formula > 0){{ '+' }}@else{{ 'до ' }}@endif{{ $agreement->formula }}%
                                                )

                                            @endif

                                        </label>
                                        
                                    </div>

                                @endforeach

                            @endif

                        </div>

                    </div>

                    <div class="input-wrapper">

                        <div class="result-block">

                            <div class="saving">
                                <div class="js-total-saving"></div>
                                <input type="hidden" name="total_savings" value="0">
                            </div>

                            <div class="to-be-paid">
                                Итого к оплате: <span class="js-result-price"></span> руб.
                                <input type="hidden" name="total_price" value="0">
                            </div>

                            <div class="submit-button">
                                <input type="hidden" name="cart_id" value="">
                                <button type="submit" class="js-submit-order">Отправить заказ</button>
                            </div>
                        
                        </div>
                        
                    </div>

                </div>

            </div>

        </form>

        <div class="item-page_products-block">

             <div class="buttons">

                @if($buy_with_cat->count() || $buy_with->count())

                    <div class="clap cart-clap clap-b-with active">
                        C этим товаром покупают
                    </div>

                @endif

                @if($buy_forget_cat->count() || $buy_forget->count())

                    <div class="clap cart-clap clap-n-forget">
                        Не забудь купить
                    </div>

                @endif

            </div>


            @if($buy_with_cat->count() || $buy_with->count())

                <div class="item-page_down-block js-d-block block-b-with active">

                    @include('catalog.snippets.cart_buy_with', [
                            'opt_style' => $opt_style,
                            'purcent_style' => $purcent_style,
                            'mr_style' => $mr_style,
                        ])

                </div>

            @endif

            @if($buy_forget_cat->count() || $buy_forget->count())

                <div class="item-page_down-block js-d-block block-n-forget">

                    @include('catalog.snippets.cart_not_forget', [
                            'opt_style' => $opt_style,
                            'purcent_style' => $purcent_style,
                            'mr_style' => $mr_style,
                        ])

                </div>

            @endif


        </div>
 --}}

    </div>
</div>

{{-- Всплывающее окно видео Youtube --}}
@include('general.popup_youtube')  

@include('general.popup_add_new_cart')

@if(session('note')) 
    @include('general.open_popup_note')
@endif 

@endsection

@section('scripts')
@parent

    <script type="text/javascript" src="{{ asset('assets/js/new_cart.js').'?v='.config('constants.version') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/js/youtube.js').'?v='.config('constants.version') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/js/picture_zoom.js').'?v='.config('constants.version') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/js/item_card_line_togglers.js').'?v='.config('constants.version') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/js/filters_line.js').'?v='.config('constants.version') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/js/togglers.js').'?v='.config('constants.version') }}"> </script>

@endsection