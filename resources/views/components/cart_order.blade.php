@props(['cartOrder' => null, 'cartOrderId' => 0])

<div
        wire:click="setCartOrderId({{$cartOrderId}})"
    class="cart-button"
{{--    class="cart-button  @if($cartOrder->id == $cartOrderId){{ 'active' }}@endif"--}}
{{--    data-cart-id="{{ $cart->id }}"--}}
{{--    data-cart-name="{{ $cart->name }}"--}}
{{--    data-count-items="{{ $cart->carts?->count() }}"--}}
{{--    data-count-all-items="{{ $cart->carts?->sum('count') }}"--}}
>
    {{ !empty($slot) ? $slot : $cartOrder->name }}
</div>
