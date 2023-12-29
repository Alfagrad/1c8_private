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
                    @yield('title-price')
                    Цена
                </div>

                <div class="purcent js-cart-head">
                    @yield('title-discount')
                    Скидка / Надбавка
                </div>

                <div class="calculated-price js-cart-head">
                    @yield('title-calculated-price')
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
            @endforeach

        </div>

    </div>

@endif
