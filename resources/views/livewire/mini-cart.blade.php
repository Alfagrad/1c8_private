<div class="main-navigation_mini-cart-block w-cart js-mini-cart-block ">

    <div class="main-navigation_mini-cart-img">

        <a
            @if(profile()->isService() ?? false)
                href="{{ route('cart.service') }}"
            @else
                href="{{ route('cart.index') }}"
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

    @if(profile()->isDealer())

        <div class="main-navigation_mini-cart-price cart-count">
            <span class="js-mini-cart-price">{{ price($sum) }}</span> руб.
        </div>

        <div class="title js-mini-cart-title" style="display: none;">
            Жми! В корзинах: позиций - {{ $positions }}, товаров - {{ $items }}, на сумму {{ price($sum) }} руб
        </div>

    @endif

</div>
