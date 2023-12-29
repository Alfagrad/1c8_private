@props([
    'item',
    'move' => null,
    'checkAmount' => null,
    'calcPrice' => null,
    'wantCheaper' => null,
    'count' => null,
    'weight' => null,
    'sum' => null,
    'delete' => null,
])

<div {{ $attributes->merge(['class' => 'catalog-item-line_wrapper']) }}>

    <div class="catalog-item-line">

        {{ $move }}

        <x-item.image :$item></x-item.image>

        <div class="catalog-item-line_name-block-wrapper">

            <div class="catalog-item-line_name-block">

                <x-item.name :$item></x-item.name>

                {{--                @if (isset($cart_item_line) && $item->amount <= 0) --}}
                {{--                    <div style='color: red; font-weight: bold;'>! Уточните наличие этой позиции у Вашего менеджера !</div> --}}
                {{--                @endif --}}

                {{ $checkAmount }}

                <x-item.cheap :$item></x-item.cheap>

                <x-item.amount :$item></x-item.amount>

            </div>

        </div>


        <x-item.opt_price :$item></x-item.opt_price>

        {{ $calcPrice }}


        {{--        {{$wantCheaper}} --}}

        {{ $count }}

        {{ $weight }}

        {{ $sum }}

        <x-item.availability :$item></x-item.availability>

        {{ $delete }}

    </div>

</div>
