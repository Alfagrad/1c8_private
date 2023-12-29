<div class="main-navigation_mini-cart-block w-cart js-mini-cart-block ">

    <div class="main-navigation_mini-cart-img">

        <a
                @if($is_service ?? false)
                    href="{{ route('serviceCartView') }}"
                @else
                    href="{{ route('newCartView') }}"
                @endif
        >
            @include('svg.cart_ico')
        </a>

        <div class="main-navigation_mini-cart-counter-wrapper">
            <div class="main-navigation_mini-cart-counter pcs">
                <span class="js-mini-cart-count">{{ $positions }}/{{ $items }}</span>
            </div>
        </div>

    </div>

    @if(!$is_service)

        <div class="main-navigation_mini-cart-price cart-count">
            <span class="js-mini-cart-price">{{ price($sum) }}</span> руб.
        </div>

        <div class="title js-mini-cart-title" style="display: none;">
            Жми! В корзинах: позиций - {{ $positions }}, товаров - {{ $items }}, на сумму {{ price($sum) }} руб
        </div>

    @endif

</div>
