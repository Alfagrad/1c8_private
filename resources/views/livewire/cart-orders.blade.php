<div>

    <div class="cart-buttons">

        <div class="cart-buttons-block">
            <div class="title">Корзины:</div>

            <x-cart_order>Основная</x-cart_order>

            @foreach($cartOrders as $cartOrder)

                <x-cart_order :$cartOrder :cartOrderId="$cartOrder->id">{{$cartOrder->name}}</x-cart_order>

            @endforeach

            <div class="cart-button add js-add-new-cart">Создать корзину</div>

            <div class="cart-button del"
                 wire:click="delete"
                 @if($cartsCount > 0)
                    wire:confirm="В {{$cartOrderName}} корзине есть товары. Удалить корзину вместе с товарами?"
                 @else
                     wire:confirm="Удалить корзину {{$cartOrderName}}?"
                 @endif
                 style="display: @if($cartOrderId == 0){{ 'none;' }}@endif"
            >
                Удалить корзину
            </div>

        </div>
    </div>

</div>
