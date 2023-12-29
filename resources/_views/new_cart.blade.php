@extends('layouts.new_app')

@section('content')



    @php
        // видимость заголовков цен в каталожной выдаче **********
//        if(isset($_COOKIE['opt_state'])) {
//            if($_COOKIE['opt_state'] == 1) {
//                $opt_style = "block";
//            } else {
//                $opt_style = "none";
//            }
//        } else {
//            $opt_style = "block";
//        }
//
//        if(isset($_COOKIE['purcent_state'])) {
//            if($_COOKIE['purcent_state'] == 1) {
//                $purcent_style = "block";
//            } else {
//                $purcent_style = "none";
//            }
//        } else {
//            $purcent_style = "block";
//        }
//
//        if(isset($_COOKIE['mr_state'])) {
//            if($_COOKIE['mr_state'] == 1) {
//                $mr_style = "block";
//            } else {
//                $mr_style = "none";
//            }
//        } else {
//            $mr_style = "none";
//        }

        // берем id корзины из куки ************************
        // if(isset($_COOKIE['cart_id'])) {
        //     $cart_id = $_COOKIE['cart_id'];
        // } else {
        //     $cart_id = '0';
        // }


    @endphp

    <div class="cart-page">
        <div class="container">

            <div class="breadcrumbs">

                <a href="{{ asset('home') }}" title="Переход на Главную страницу">
                    Главная
                </a>

                <span>|</span>

                <strong>Корзина &laquo;<span class="name js-cart-name">Основная</span>&raquo;</strong>

            </div>

            @if(profile()->isDealer())
                <livewire:carts-dealer />
            @else
                <livewire:carts-service />
            @endif





{{--            <form method="post" action="{{ asset('/cart-page/order-create') }}" class="js-order-form">--}}

{{--                {{ csrf_field() }}--}}
{{--                <input type="hidden" name="cart_id" value="0">--}}

{{--                <div class="carts-block">--}}

{{--                    @if($carts->count() > 0)--}}

{{--                        <div--}}
{{--                            class="carts-element js-cart js-cart-id-{{ $cart['cart_id'] }}"--}}
{{--                            data-cart-id="{{ $cart['cart_id'] }}"--}}
{{--                        >--}}

{{--                            <x-cart_table title="Товары"--}}
{{--                                          :carts="$carts->filter(fn($cart) => $cart->item->is_component == 0)"></x-cart_table>--}}

{{--                            <x-cart_table title="Запчасти"--}}
{{--                                          :carts="$carts->filter(fn($cart) => $cart->item->is_component == 1)"></x-cart_table>--}}

{{--                            <div class="cart-total-line">--}}

{{--                                <div class="cart-manipulate-block js-drop-down-title">--}}

{{--                                    <div class="title-block">--}}

{{--                                        <div class="title">Переместить/копировать товары...</div>--}}

{{--                                        <div class="arrow">--}}
{{--                                            @include('svg.phones_arrow_ico')--}}
{{--                                        </div>--}}

{{--                                    </div>--}}

{{--                                    <div class="drop-down-block js-drop-down" style="display: none;">--}}

{{--                                        <div class="relocate-block">--}}

{{--                                            <div class="title">--}}
{{--                                                Перенести в:--}}
{{--                                            </div>--}}

{{--                                            <div class="relocate-links-block">--}}

{{--                                                @foreach($carts as $cart_link)--}}

{{--                                                    @if($cart_link['cart_id'] == $cart['cart_id'])--}}
{{--                                                        @continue--}}
{{--                                                    @endif--}}

{{--                                                    <div class="relocate-link js-relocate"--}}
{{--                                                         data-cart-id="{{ $cart_link['cart_id'] }}">--}}
{{--                                                        {{ $cart_link['name'] }}--}}
{{--                                                    </div>--}}

{{--                                                @endforeach--}}

{{--                                            </div>--}}

{{--                                        </div>--}}

{{--                                        <div class="relocate-block">--}}

{{--                                            <div class="title">--}}
{{--                                                Копировать в:--}}
{{--                                            </div>--}}

{{--                                            <div class="relocate-links-block">--}}

{{--                                                @foreach($carts as $cart_link)--}}

{{--                                                    @if($cart_link['cart_id'] == $cart['cart_id'])--}}
{{--                                                        @continue--}}
{{--                                                    @endif--}}

{{--                                                    <div class="relocate-link js-copy"--}}
{{--                                                         data-cart-id="{{ $cart_link['cart_id'] }}">--}}
{{--                                                        {{ $cart_link['name'] }}--}}
{{--                                                    </div>--}}

{{--                                                @endforeach--}}

{{--                                            </div>--}}

{{--                                        </div>--}}

{{--                                        <div class="relocate-block">--}}

{{--                                            <div class="relocate-links-block">--}}

{{--                                                <div class="relocate-link js-delete-items"--}}
{{--                                                     data-cart-id="{{ $cart['cart_id'] }}">--}}
{{--                                                    Удалить выбранное из корзины--}}
{{--                                                </div>--}}

{{--                                            </div>--}}

{{--                                        </div>--}}

{{--                                        @if($cart['cart_id'] != 0)--}}

{{--                                            <div class="relocate-block">--}}

{{--                                                <div class="relocate-links-block">--}}

{{--                                                    <div class="relocate-link js-swapping"--}}
{{--                                                         data-cart-id="{{ $cart['cart_id'] }}">--}}
{{--                                                        Поменять местами с Основной корзиной--}}
{{--                                                    </div>--}}

{{--                                                </div>--}}

{{--                                            </div>--}}

{{--                                        @endif--}}

{{--                                    </div>--}}

{{--                                </div>--}}

{{--                                <div></div>--}}

{{--                                <div class="result-info-block">--}}
{{--                                    <div>Итого:</div>--}}
{{--                                    <div><span class="js-total-weight"></span> кг</div>--}}
{{--                                    <div><span class="js-total-price"></span> руб.</div>--}}
{{--                                </div>--}}

{{--                            </div>--}}

{{--                            <div class="cart-empty-block">--}}
{{--                                <div class="cart-empty-link js-empty-cart">--}}
{{--                                    Очистить корзину <strong>&laquo;{{ $cart['name'] }}&raquo;</strong>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}

{{--                    @else--}}

{{--                        <div--}}
{{--                            class="carts-element no-items js-cart js-cart-id-{{ $cart['cart_id'] }}" @if($cart['cart_id'] != $cart_id)--}}
{{--                            {!! "style='display: none;'" !!}--}}
{{--                            @endif>--}}
{{--                            Корзина <strong>&laquo;{{ $cart['name'] }}&raquo;</strong> пуста--}}
{{--                        </div>--}}

{{--                    @endif--}}

{{--                </div>--}}

{{--                <div class="cart-empty-block">--}}
{{--                    <div class="cart-empty-link js-empty-all-carts">--}}
{{--                        Очистить <strong>ВСЕ</strong> корзины--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="cart-order-completion-wrapper">--}}

{{--                    <div class="cart-order-completion-block js-completion-block" data-cart-id="{{ $cart_id }}">--}}

{{--                        <div class="input-wrapper">--}}

{{--                            <div class="input-block">--}}

{{--                                <div class="title">Выберите способ доставки:</div>--}}

{{--                                @if($delivery_addresses->count())--}}

{{--                                    <div class="input-element">--}}

{{--                                        <label>--}}
{{--                                            <input--}}
{{--                                                type="radio"--}}
{{--                                                name="delivery_type"--}}
{{--                                                value="2"--}}
{{--                                                required--}}
{{--                                                class="js-no-pickup"--}}
{{--                                            >--}}
{{--                                            Доставка--}}
{{--                                        </label>--}}

{{--                                    </div>--}}

{{--                                @endif--}}

{{--                                <div class="input-element">--}}

{{--                                    <label>--}}
{{--                                        <input--}}
{{--                                            type="radio"--}}
{{--                                            name="delivery_type"--}}
{{--                                            value="1"--}}
{{--                                            required--}}
{{--                                            class="js-pickup"--}}
{{--                                        @if(!$delivery_addresses->count())--}}
{{--                                            {{ 'checked' }}--}}
{{--                                            @endif--}}
{{--                                        >--}}
{{--                                        Самовывоз--}}
{{--                                    </label>--}}
{{--                                </div>--}}

{{--                            </div>--}}

{{--                            @if($delivery_addresses->count())--}}

{{--                                <div class="input-block">--}}

{{--                                    <div class="title">Выберите адрес доставки:</div>--}}

{{--                                    @foreach($delivery_addresses as $addr)--}}

{{--                                        <div class="input-element">--}}

{{--                                            <label>--}}

{{--                                                <input--}}
{{--                                                    type="radio"--}}
{{--                                                    name="delivery_address"--}}
{{--                                                    value="{{ $addr['uuid'] }}"--}}
{{--                                                    class="js-delivery-address"--}}
{{--                                                    required--}}
{{--                                                    disabled--}}
{{--                                                >--}}
{{--                                                {{ $addr['address'] }}--}}

{{--                                            </label>--}}

{{--                                        </div>--}}

{{--                                    @endforeach--}}

{{--                                </div>--}}

{{--                            @endif--}}

{{--                            <div class="delivery-comment">--}}

{{--                                <div class="title">Дополнительный комментарий к заказу:</div>--}}

{{--                                <textarea name="comment_to_order"></textarea>--}}

{{--                            </div>--}}

{{--                        </div>--}}

{{--                        <div class="input-wrapper">--}}

{{--                            <div class="input-block">--}}

{{--                                <div class="title">Выберите соглашение:</div>--}}

{{--                                @if($common_agreements->count())--}}

{{--                                    <div class="title">Типовое:</div>--}}

{{--                                    @foreach($common_agreements as $agreement)--}}

{{--                                        <div class="input-element ">--}}

{{--                                            <label>--}}

{{--                                                <input--}}
{{--                                                    class="js-paying-type"--}}
{{--                                                    type="radio"--}}
{{--                                                    name="calc_type"--}}
{{--                                                    value="{{ $agreement->uuid }}"--}}
{{--                                                    data-calc_discount="{{ $agreement->formula }}"--}}
{{--                                                    data-agreement_items="{{ $agreement->items_str }}"--}}
{{--                                                    required--}}
{{--                                                >--}}

{{--                                                {{ $agreement->name }}--}}

{{--                                                @if($agreement->formula)--}}

{{--                                                    (--}}
{{--                                                    @if($agreement->formula > 0)--}}
{{--                                                        {{ '+' }}--}}
{{--                                                    @else--}}
{{--                                                        {{ 'до ' }}--}}
{{--                                                    @endif{{ $agreement->formula }}%--}}
{{--                                                    )--}}

{{--                                                @endif--}}

{{--                                            </label>--}}

{{--                                        </div>--}}

{{--                                    @endforeach--}}

{{--                                @endif--}}

{{--                                @if($personal_agreements->count())--}}

{{--                                    <div class="title">Индивидуальное:</div>--}}

{{--                                    @foreach($personal_agreements as $agreement)--}}

{{--                                        <div class="input-element js-personal-agreement">--}}

{{--                                            <label>--}}

{{--                                                <input--}}
{{--                                                    class="js-paying-type"--}}
{{--                                                    type="radio"--}}
{{--                                                    name="calc_type"--}}
{{--                                                    value="{{ $agreement->uuid }}"--}}
{{--                                                    data-calc_discount="{{ $agreement->formula }}"--}}
{{--                                                    data-partner_uuid="{{ $agreement->partner }}"--}}
{{--                                                    data-agreement_items="{{ $agreement->items_str }}"--}}
{{--                                                    required--}}
{{--                                                    disabled--}}
{{--                                                >--}}

{{--                                                {{ $agreement->name }}--}}

{{--                                                @if($agreement->formula)--}}

{{--                                                    (--}}
{{--                                                    @if($agreement->formula > 0)--}}
{{--                                                        {{ '+' }}--}}
{{--                                                    @else--}}
{{--                                                        {{ 'до ' }}--}}
{{--                                                    @endif{{ $agreement->formula }}%--}}
{{--                                                    )--}}

{{--                                                @endif--}}

{{--                                            </label>--}}

{{--                                        </div>--}}

{{--                                    @endforeach--}}

{{--                                @endif--}}

{{--                            </div>--}}

{{--                        </div>--}}

{{--                        <div class="input-wrapper">--}}

{{--                            <div class="result-block">--}}

{{--                                <div class="saving">--}}
{{--                                    <div class="js-total-saving"></div>--}}
{{--                                    <input type="hidden" name="total_savings" value="0">--}}
{{--                                </div>--}}

{{--                                <div class="to-be-paid">--}}
{{--                                    Итого к оплате: <span class="js-result-price"></span> руб.--}}
{{--                                    <input type="hidden" name="total_price" value="0">--}}
{{--                                </div>--}}

{{--                                <div class="submit-button">--}}
{{--                                    <input type="hidden" name="cart_id" value="">--}}
{{--                                    <button type="submit" class="js-submit-order">Отправить заказ</button>--}}
{{--                                </div>--}}

{{--                                <div class="note">--}}
{{--                                    * Внимание!<br>Заказы на сумму менее 100 руб не принимаем!--}}
{{--                                </div>--}}

{{--                            </div>--}}

{{--                        </div>--}}

{{--                    </div>--}}

{{--                </div>--}}

{{--            </form>--}}

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

    @vite(['resources/js/new_cart.js'])

    <script type="text/javascript"
            src="{{ asset('assets/js/new_cart.js').'?v='.config('constants.version') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/youtube.js').'?v='.config('constants.version') }}"></script>
    <script type="text/javascript"
            src="{{ asset('assets/js/picture_zoom.js').'?v='.config('constants.version') }}"></script>
    <script type="text/javascript"
            src="{{ asset('assets/js/item_card_line_togglers.js').'?v='.config('constants.version') }}"></script>
    <script type="text/javascript"
            src="{{ asset('assets/js/filters_line.js').'?v='.config('constants.version') }}"></script>
    <script type="text/javascript"
            src="{{ asset('assets/js/togglers.js').'?v='.config('constants.version') }}"></script>

@endsection
