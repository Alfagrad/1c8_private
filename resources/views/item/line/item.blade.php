@php

    $item = $item ?? $cart->item;

    // ***********************************************

    // назначаем класс для фильтра "Новинка"
    if ($item->is_new_item) {
        $js_new_item = 'js_new_item';
    } else {
        $js_new_item = '';
    }

    // назначаем класс для фильтра "Акции"
    if ($item->discounted != 0) {
        $js_action = 'js_action';
    } else {
        $js_action = '';
    }

    // назначаем класс для фильтров наличия
    if ($item->amount > 0) {
        $availability_class = 'js-avalible-item';
    } elseif ($item->count_type == 2) {
        $availability_class = 'js-reserve';
    } elseif ($item->count_type == 3) {
        $availability_class = 'js-soon';
    } else {
        $availability_class = 'js-out-of-stock';
    }

    // ***********************************************
    // назначаем класс для фильтруемых по слову линий
    if (!isset($line_four)) {
        // исключаем фильтрацию
        $filtred_line_class = 'js-filtred-item';
    } else {
        $filtred_line_class = '';
    }

    // ***********************************************
    // видимость цен в каталожной выдаче
    if (!isset($cart_item_line)) {
        // если это не линия корзины
        if (isset($_COOKIE['opt_state'])) {
            if ($_COOKIE['opt_state'] == 1) {
                $item_line_opt_style = 'flex';
            } else {
                $item_line_opt_style = 'none';
            }
        } else {
            $item_line_opt_style = 'flex';
        }

        if (isset($_COOKIE['purcent_state'])) {
            if ($_COOKIE['purcent_state'] == 1) {
                $item_line_purcent_style = 'flex';
            } else {
                $item_line_purcent_style = 'none';
            }
        } else {
            $item_line_purcent_style = 'flex';
        }

        if (isset($_COOKIE['mr_state'])) {
            if ($_COOKIE['mr_state'] == 1) {
                $item_line_mr_style = 'flex';
            } else {
                $item_line_mr_style = 'none';
            }
        } else {
            $item_line_mr_style = 'none';
        }

        if (isset($_COOKIE['mr_state']) && isset($_COOKIE['opt_state']) && isset($_COOKIE['purcent_state'])) {
            if ($_COOKIE['mr_state'] == 1 && $_COOKIE['opt_state'] == 0 && $_COOKIE['purcent_state'] == 0) {
                $action_string_style = $cheep_item_style = 'none';
            } else {
                $action_string_style = $cheep_item_style = 'inline';
            }
        } else {
            $action_string_style = $cheep_item_style = 'inline';
        }
        $cart_item_line_class = '';
    } else {
        $item_line_opt_style = 'flex';
        $item_line_purcent_style = $item_line_mr_style = 'none';
        $action_string_style = $cheep_item_style = 'inline';
        $cart_item_line_class = 'cart-item-line';
    }

@endphp

<div class='catalog-item-line_wrapper {{$class}}'>

    <div class="catalog-item-line">

        @yield('move')
        {{--        {{ $move }}--}}


        <x-item.image :$item></x-item.image>

        <div class="catalog-item-line_name-block-wrapper">

            <div class="catalog-item-line_name-block">

                <x-item.name :$item></x-item.name>

                {{--                @if (isset($cart_item_line) && $item->amount <= 0) --}}
                {{--                    <div style='color: red; font-weight: bold;'>! Уточните наличие этой позиции у Вашего менеджера !</div> --}}
                {{--                @endif --}}

                @yield('checkAmount')
                {{--                {{ $checkAmount }}--}}

                <x-item.cheap :$item></x-item.cheap>

                <x-item.amount :$item></x-item.amount>

            </div>

        </div>

        @yield('price')


        {{--        {{ $calcPrice }}--}}


        {{--        {{$wantCheaper}} --}}

        <x-item.want_cheaper :$item></x-item.want_cheaper>
        @yield('count')
{{--        <div class="catalog-item-line_input-block-wrapper">--}}
{{--            @if ($item->amount > 0)--}}
{{--                <livewire:count :cart="$carts->get($item->id_1c)" :$item :key="$item->uuid"/>--}}
{{--            @endif--}}
{{--        </div>--}}

        @yield('weight')
        {{--        {{ $weight }}--}}

        @yield('sum')
        {{--        {{ $sum }}--}}

        <x-item.availability :$item></x-item.availability>

        @yield('delete')
        {{--        {{ $delete }}--}}

    </div>

</div>


{{--<x-item.line :$item--}}
{{--    class="catalog-item-line_wrapper js-item-element {{ $filtred_line_class }}  $analog_wrapper_class  {{ $js_new_item }}  $analog_line_class  {{ $js_action }}  $availability_class   $line_four_class   $scheme_item_line_class  {{ $cart_item_line_class }}">--}}

{{--    <div class="catalog-item-line">--}}

{{--        <x-item.image :$item></x-item.image>--}}

{{--        <x-slot:calcPrice>--}}
{{--            <x-item.markup :$item></x-item.markup>--}}
{{--            <x-item.mr_price :$item></x-item.mr_price>--}}
{{--        </x-slot:calcPrice>--}}

{{--        <x-item.norm_hours :$item></x-item.norm_hours>--}}

{{--        <x-slot:count>--}}
{{--            <x-item.want_cheaper :$item></x-item.want_cheaper>--}}
{{--            <div class="catalog-item-line_input-block-wrapper">--}}
{{--                @if ($item->amount > 0)--}}
{{--                    <livewire:count :cart="$carts->get($item->id_1c)" :$item :key="$item->uuid" />--}}
{{--                @endif--}}
{{--            </div>--}}
{{--        </x-slot:count>--}}

{{--    </div>--}}

{{--</x-item.line>--}}
