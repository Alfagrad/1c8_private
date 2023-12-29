<div>

    @yield('cart-orders')

    <div class="carts-block">

        @if($this->carts->count() > 0)

            <div class="">
                @yield('tables')
                @yield('relocate')
                {{--                <div class="cart-empty-block">--}}
                {{--                    <div class="cart-empty-link js-empty-cart">--}}
                {{--                        Очистить корзину <strong>&laquo;{{ $cartOrder['name'] }}&raquo;</strong>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
            </div>

        @else
            <div
                class="carts-element no-items">
                Корзина <strong>&laquo;{{ $cartOrderName }}&raquo;</strong> пуста
            </div>
        @endif

    </div>

    {{--    <div class="cart-empty-block">--}}
    {{--        <div wire:click="deleteAll" wire:confirm="Очистить все корзины. Продолжить?" class="cart-empty-link">--}}
    {{--            Очистить <strong>ВСЕ</strong> корзины--}}
    {{--        </div>--}}
    {{--    </div>--}}

    <div class="cart-order-completion-wrapper">
        <div class="cart-order-completion-block js-completion-block">
            @yield('order')
        </div>
    </div>

</div>
