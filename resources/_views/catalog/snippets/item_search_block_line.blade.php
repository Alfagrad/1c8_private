@php

// определяем цену
if ($item->adjustable == 1) { // если товар регулируемый
    $price = $item->price_rub;
    $price_mr = $item->price_mr_rub;
} else {
    // считаем по оптовому курсу
    $price = number_format($item->price_usd * $usd_opt, 2, '.', '');
    $price_mr = number_format($item->price_mr_usd * $usd_mr, 2, '.', '');
}

// формируем строку скидок для акционных товаров
if($item->discount_str) {

    // делим скидки
    $discounts = explode(';', $item->discount_str);

    // формируем строку дисконта 
    $discount_str = '';
    $i = 1;
    $discount_count = count($discounts);

    foreach ($discounts as $element) {

        // делим элементы скидки
        $element = explode('|', $element);

        // мин кол-во на которое действует
        $condition = $element[0];
        // цена
        $discount_price = $element[1];
        // дата начала
        $date_start = $element[2];
        // дата окончания
        $date_end = $element[3];

        if (date('Y-m-d') >= $date_start && ($date_end == '0000-00-00' || date('Y-m-d') <= $date_end)) {

            $discount_str .= "от {$condition} шт ".number_format($discount_price * 1.2, 2, '.', '')." руб, ";

        }

        $i++;
    }

    // удаляем запятую и пробел в конце строки
    $discount_str = preg_replace('/, $/', '', $discount_str);

    if ($discount_str) {

        // минимальная цена
        $min_price = $discount_price;

        if ($discount_count == 1 && $condition == 1) {
            // метка зачеркивания старой цены цены
            $line_through = 1;
        } else {
            $line_through = '';
        }
    }
}

// ***********************************************
// видимость цен в каталожной выдаче
if(isset($_COOKIE['opt_state'])) {
    if($_COOKIE['opt_state']) {
        $item_line_opt_style = "flex";
    } else {
        $item_line_opt_style = "none";
    }
} else {
    $item_line_opt_style = "flex";
}

if(isset($_COOKIE['mr_state'])) {
    if($_COOKIE['mr_state']) {
        $item_line_mr_style = "flex";
    } else {
        $item_line_mr_style = "none";
    }
} else {
    $item_line_mr_style = "none";
}

if(isset($_COOKIE['mr_state']) && isset($_COOKIE['opt_state']) && isset($_COOKIE['purcent_state'])) {
    if($_COOKIE['mr_state'] == 1 && $_COOKIE['opt_state'] == 0 && $_COOKIE['purcent_state'] == 0) {
        $action_string_style = $cheep_item_style = "none";
    } else {
        $action_string_style = $cheep_item_style = "inline";
    }
} else {
    $action_string_style = $cheep_item_style = "inline";
}

// если нет параметра упаковки, ставим 1
if($item->packaging) {
    $packaging = $item->packaging;
} else {
    $packaging = 1;
}

@endphp

<div class="row dropper">
    <a href="{{ route('itemCard', ['itemId' => $item->id_1c]) }}" class="" title="{{ $item->name }}">

        @php
            $name = preg_replace($patterns, $replace, $item->name);
        @endphp

        {!! $name !!}
    </a>

    <div class="arrow"></div>

</div>

<div class="inset js-item-element">

    @if ($item->image_mid)

        <a href="{{ route('itemCard', ['itemId' => $item->id_1c]) }}" class="w-image ">
            <img src="{{ asset('storage/ut_1c8/item-images/'.$item->image_mid) }}">

        </a>

    @endif

    <div class="name">
        <a href="{{ route('itemCard', ['itemId' => $item->id_1c]) }}" class="">
            {{ $item->name }}
        </a>

        @if(trim($item->discount_str) && $discount_str)

            <br>
            <span class="js-action-string" style="color: red; display: {{ $action_string_style }}">
                {{ $discount_str }}
            </span>

        @endif

    </div>

    <div class="sale-line">

        @if ($item->discount_str)

            <div class="action-sing">АКЦИЯ</div>

        @elseif ($item->spec_price == 1)

            <div class="action-sing spec">Спецпредложение</div>

        @endif

    </div>


{{--     @if($item->discounted_rub && $item->is_action)

    <div class="sale-line">
        <div class="sticker _sale">
            {{ $discountString }}
        </div>


        @if($item->mini_text != '')

        <div class="roll inline-roll _sale" title="{{ $item->mini_text }}">
            %
        </div>

        @endif

    </div>


    @endif --}}

    <div class="article">
        Код: <span>{{ $item->id_1c }}</span>
    </div>

    <div class="price-line js-mr" style="display: {{ $item_line_mr_style }};">

        <div class="text-before">
            Цена МРЦ
        </div>

        <div class="price mr">
            {{ number_format($price_mr * 1.2, 2, '.', '') }} руб
        </div>

    </div>

    <div class="price-line js-opt" style="display: {{ $item_line_opt_style }};">

        <div class="text-before">
            Цена ОПТ
        </div>

        @if(isset($discount_str) && $discount_str)

            <div class="price" style="background-color: red;">

                {{ number_format($min_price * 1.2, 2, '.', '') }} руб

                @php
                    if (isset($line_through) && $line_through == 1) {
                        $st_str = " text-decoration: line-through;";
                    } else {
                        $st_str = "";
                    }
                @endphp

                <div class="content-show-old-price" style="font-size: 16px;{{ $st_str }}">
                    {{ number_format($price * 1.2, 2, '.', '') }} руб
                </div>

            </div>

        @else

            <div class="price">
                {{ number_format($price * 1.2, 2, '.', '') }} руб
            </div>

        @endif

    </div>

    <div class="in-stock">

        @if($item->amount > 0 && $item->amount_type == 1)

            @if($item->amount > 10)

                <div class="td-valible">
                    <div class="icon _yes _verylot">
                        В наличии
                        @if(Auth::user()->role_id != '2')
                        <br><strong>{{ $item->amount }} шт.</strong>
                        @else
                        <br><strong>>10 шт.</strong>
                        @endif
                    </div>
                </div>

            @elseif($item->amount >= 5 and $item->amount <= 10 )

                <div class="td-valible">
                    <div class="icon _yes _lot">
                        В наличии
                        <br><strong>{{ $item->amount }} шт.</strong>
                    </div>
                </div>

            @else

                <div class="td-valible">
                    <div class="icon _yes">
                        В наличии
                        <br><strong>{{ $item->amount }} шт.</strong>
                    </div>
                </div>

            @endif

        @elseif($item->amount_type == 2)

            <div class="td-valible">
                <div class="icon _no _reserved">Резерв</div>
            </div>

        @elseif($item->amount_type == 3)

            <div class="td-valible">
                <div class="icon _no">{{$item->amount_text}} </div>
            </div>

        @elseif($item->amount_type == 4)

            <div class="td-valible">
                <div class="icon">Нет на складе</div>
            </div>

        @elseif($item->amount_type == 5)

            <div class="td-valible">
                <div class="icon" title="Звоните">
                    Уточните наличие

                    @if(Auth::user()->role_id != '2')

                    <br><strong>{{ $item->amount }} шт.</strong>

                    @endif

                </div>
            </div>

        @endif
    </div>

    <div class="centered">
        <div class="td-pcs">

        @if($item->amount > 0 && $item->amount_type != 5)

            <div class="pcs-controll minus js-search-remove-from-cart">-</div>

            @php
                // если есть в корзине, отображаем количество
                if(isset($in_cart[$item->id_1c])) {
                    $count_in_cart = $in_cart[$item->id_1c];
                } else {
                    $count_in_cart = 0;
                }
                // узнаем предыдущий роут
                $url = url()->previous();
                $route = app('router')->getRoutes($url)->match(app('request')->create($url))->getName();
            @endphp

            <input
                type="number"
                name="item_count"
                class="js-count-input"
                value="{{ $count_in_cart }}"
                data-step="{{ $packaging }}" 
                data-id_1c="{{ $item->id_1c }}"
                data-rout_name="{{ $route }}"
                onfocus="this.removeAttribute('readonly');"
                readonly
                autocomplete="off"
            >

            <div class="pcs-controll plus js-search-add-to-cart">+</div>

        @endif

        </div>
    </div>

    @if($item->amount > 0 && $item->in_archive != 1 && $packaging > 1)

        <div
            class="packaging-string js-search-packaging plus"
            data-block_id="{{ $packaging }}"
            data-item_id="{{ $item->id }}"
            title="Жми, чтобы добавить {{$packaging}} шт."
        >
            В упаковке {{ $packaging }} шт.
        </div>

    @endif

</div>
