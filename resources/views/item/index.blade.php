@extends('layouts.app')

@section('content')

    @php

        // определяем цену
        //if ($item_card->adjustable == 1) {
        //    $price = $item_card->price_rub;
        //    $price_mr = $item_card->price_mr_rub;
        //} else {
        //    // считаем по оптовому курсу
        //    $price = number_format($item_card->price_usd * $usd_opt, 2, '.', '');
        //    $price_mr = number_format($item_card->price_mr_usd * $usd_mr, 2, '.', '');
        //}

        $price = $item_card->price_rub;
        $price_mr = $item_card->price_mr_rub;

        // формируем строку скидок для акционных товаров

            // // формируем строку скидок для акционных товаров
            // if($item_card->discounted_rub) {
            //     // делим скидки
            //     $discounts = explode(';', $item_card->discounted_rub);
            //     // убираем пустые значения
            //     $discounts = array_diff($discounts, array(''));
            //     // отделяем количество от цены
            //     foreach ($discounts as $pLD) {
            //         if ($pLD) {
            //             list($count, $price) = explode('-', $pLD);
            //             $listDiscount[] = [
            //                 'count' => $count,
            //                 'price' => $price
            //             ];
            //         }
            //     }
            //     // формируем строку (с использованием markup)
            //     $actionString = '';
            //     $actionString_2 = '';
            //     foreach ($listDiscount as $price) {
            //         $actionString .= ' от ' . $price['count'] . ' шт ' . number_format(ceil((float)$price['price'] * $markupBYN * 100) / 100, 2) . ' руб, ';
            //         $actionString_2 .= ' от ' . $price['count'] . ' шт ' . number_format(ceil((float)$price['price'] * $markupBYN * 100) / 100, 2) . ' руб<br>';
            //     }
            //     if ($actionString) {
            //         $actionString = substr($actionString, 0, -2);
            //         $actionString_2 = substr($actionString_2, 0, -4);
            //     }

            //     if($item_card->is_action) {
            //         // расчитываем % скидки для акции
            //         $end = end($listDiscount);
            //         if($item_card->bel_price != 0) $maxPrs = round((1 - ((float)$end['price'] * $markupBYN) / ($item_card->bel_price * $markupBYN)) * 100);
            //             else $maxPrs = 0;
            //         $discountString = "Акция - ".$maxPrs."%";
            //     }
            // }

            // видимость заголовков цен в каталожной выдаче **********
            if(isset($_COOKIE['opt_state'])) {
                if($_COOKIE['opt_state'] == 1) {
                    $opt_style = "block";
                } else {
                    $opt_style = "none";
                }
            } else {
                $opt_style = "block";
            }

            if(isset($_COOKIE['purcent_state'])) {
                if($_COOKIE['purcent_state'] == 1) {
                    $purcent_style = "block";
                } else {
                    $purcent_style = "none";
                }
            } else {
                $purcent_style = "block";
            }

            if(isset($_COOKIE['mr_state'])) {
                if($_COOKIE['mr_state'] == 1) {
                    $mr_style = "block";
                } else {
                    $mr_style = "none";
                }
            } else {
                $mr_style = "none";
            }

            // невидимость акционной строки в режиме МРЦ
            if(isset($_COOKIE['mr_state']) && isset($_COOKIE['opt_state']) && isset($_COOKIE['purcent_state'])) {
                if($_COOKIE['mr_state'] == 1 && $_COOKIE['opt_state'] == 0 && $_COOKIE['purcent_state'] == 0) {
                    $action_string_style = "none";
                } else {
                    $action_string_style = "inline";
                }
            } else {
                $action_string_style = "inline";
            }

            // если нет параметра упаковки, ставим 1
            if(intval($item_card->packaging)) {
                $packaging = intval($item_card->packaging);
            } else {
                $packaging = 1;
            }

    @endphp

    <div class="item-page">
        <div class="container">

            @if(isset($breadcrumbs))

                <div class="breadcrumbs">

                    <a href="{{ asset('home') }}" title="Переход на Главную страницу">
                        Главная
                    </a>
                    <span>|</span>

                    @foreach($breadcrumbs as $breadcrumb)

                        <a
                            @if(!$loop->first)
                                href="{{asset('catalogue/'.$breadcrumb['id'])}}"
                            title="Переход в категорию {{ $breadcrumb['name'] }}"
                            @endif
                        >
                            {{ $breadcrumb['name'] }}
                        </a>

                        @if(!$loop->last)

                            <span>→</span>

                        @endif

                    @endforeach

                </div>

            @endif

            <div class="item-page_name">

                <div class="name">
                    {{ $item_card->name }}
                    Артикул: {{ $item_card->vendor_code }}
                </div>

                @if(trim($item_card->discount_str) && $discount_str)

                    <span class="js-action-string" style="color: red; display: {{ $action_string_style }};">
                    {{ $discount_str }}
                </span>

                @endif

            </div>

            <section class="item-page_info-block">

                <div class="item-page_images-block">

                    <div class="item-page_big-images js-big-images" data-img-count="{{ count($images) }}">

                        @if(count($images) > 1)

                            <div class="item-page_image-corner left js-left">
                                @include('svg.image_corner')
                            </div>

                        @endif

                        <div class="item-page_image">

                            @if($item_card->is_new_item == 1)

                                <div class="new">NEW</div>

                            @endif

                            @php
                                $bnt = 1;
                            @endphp

                            @forelse($images as $image)

                                @php
                                    if($loop->first) {
                                        $disp = "block";
                                        $bcls = "active";
                                    } else {
                                        $disp = "none";
                                        $bcls = "";
                                    }
                                @endphp

                                <a href="{{ asset('storage/ut_1c8/item-images/'.$image['image']) }}"
                                   class="js-big-pic {{ $bcls }}"
                                   data-fancybox='images'
                                   data-caption="Изображение {{ $bnt }}"
                                   data-big-img-nun="{{ $bnt }}"
                                   style="display: {{ $disp }};"
                                >
                                    <div class="image-wrapper">
                                        <img src="{{ asset('storage/ut_1c8/item-images/'.$image['image']) }}">
                                    </div>
                                </a>

                                @php
                                    $bnt++
                                @endphp

                            @empty

                                <img src="{{ asset('upload/no-thumb.png') }}">

                            @endforelse

                        </div>

                        @if(count($images) > 1)

                            <div class="item-page_image-corner right js-right">
                                @include('svg.image_corner')
                            </div>

                        @endif

                    </div>

                    @php $cnt = 1; @endphp

                    @if(count($images) > 1)

                        <div class="item-page_thumb-images">

                            @foreach($images as $image)

                                @php
                                    if($cnt == 1) $cls = "active";
                                        else $cls = "";
                                @endphp

                                <div class="item-page_thumb-element {{ $cls }} js-img" data-img-num="{{ $cnt }}">
                                    <img src="{{ asset('storage/ut_1c8/item-images/'.$image['image_sm']) }}">
                                </div>

                                @php $cnt++ @endphp

                            @endforeach

                        </div>

                    @endif

                    <div class="item-page_thumb-images">

                        @if(trim($item_card->youtube))

                            @php
                                $youtube_array = array_diff(explode(';', trim($item_card->youtube)), array('', NULL, false));
                            @endphp

                            @foreach($youtube_array as $youtube_link)

                                @if(count(explode('=', $youtube_link)) > 1)
                                    @php
                                        $youtube_code = explode('=', $youtube_link)[1];
                                    @endphp
                                @else
                                    @php
                                        $youtube_code = explode('.be/', $youtube_link)[1];
                                    @endphp
                                @endif

                                <div class="item-page_thumb-element" data-slide-index="{{ $cnt }}">
                                    <div
                                        class="youtube js_video_link"
                                        title="Смотреть видео о товаре на Youtube"
                                        video="{{ $youtube_code }}"
                                    >
                                        <img src="{{ asset('assets/img/youtube.png') }}">
                                    </div>
                                    <img src="@if($item_card->images->count()){{
                                        asset('storage/ut_1c8/item-images/'.$images[0]['image_sm'])
                                    }}@else{{
                                        asset('upload/no-thumb.png')
                                    }}@endif"
                                    >
                                </div>

                                @php $cnt++ @endphp

                            @endforeach

                        @endif

                        @if($item_card->guides->count())

                            @foreach($item_card->guides as $guide)

                                @php $add = "" @endphp

                                @if($item_card->guides->count() > 1)
                                    @php $add = " ".$loop->iteration @endphp
                                @endif

                                <a href="{{ asset('storage/ut_1c8/item-guides/'.$guide->file) }}" class="item-page_thumb-element" target="_blank" title="скачать инструкцию по эксплуатации">
                                    <img src="{{ asset('assets/img/doc.png') }}">
                                    <div>Инструкция{{ $add }}</div>
                                </a>

                            @endforeach

                        @endif

                        <a href="{{ asset('/pricetag/form/'.$item_card->{'1c_id'}) }}" class="item-page_thumb-element pricetag" target="_blank" title="Подготовить и скачать ценник">
                            <img src="{{ asset('assets/img/pr_tag.png') }}">
                            <div>Ценник</div>
                        </a>

                    </div>

                </div>

                <div class="item-page_right-block">

                    <div class="item-page_price-block js-price-block">

                        @if(profile()->isDealer())

                            <div class="code-line">
                                <div class="product-code">
                                    Код: {{$item_card->id_1c}}
                                </div>

                                @php

                                    if($item_card->amount > 0) {

                                        $txt = "В наличии";
                                        $cls = "in-stock-few";
                                        $ttl = "на складе ".$item_card->count." шт.";
                                        $cn = $item_card->amount. " шт.";

                                        if($item_card->count >= 5) {
                                            $cls = "in-stock-normal";
                                        }

                                        if($item_card->count > 10) {
                                            $cls = "in-stock-lot";
                                            $ttl = "Доступно более 10шт";
                                            if(Auth::user()->role_id == '2') $cn = ">10 шт.";
                                        }

                                    } elseif($item_card->reserve > 0) {

                                        $txt = "Резерв";
                                        $cls = "";
                                        $cn = "";
                                        $ttl = "Звоните!";

                                    } elseif($item_card->locked > 0) {

                                        $txt = "Уточните наличие";
                                        $cls = "";
                                        if(Auth::user()->role_id == '2') {
                                            $cn = "(>10 шт.)";
                                        } else {
                                            $cn = "(".$item_card->amount. " шт.)";
                                        }
                                        $ttl = "Уточните наличие";

                                    } elseif($item_card->expected > 0) {

                                        $txt = date('d.m.Y', strtotime($item_card->expected_date));
                                        $cls = "in-stock-transit";
                                        $cn = "";
                                        $ttl = "Поступит ".date('d.m.Y', strtotime($item_card->expected_date));

                                    } else {

                                        $txt = "Нет";
                                        $cls = "";
                                        $cn = "";
                                        $ttl = "Нет на складе";

                                    }


                                @endphp

                                <div class="product-count">
                                    <div class="icon {{ $cls }}" title="{{ $ttl }}">
                                        {{ $txt }} {{ $cn }}
                                    </div>
                                </div>

                            </div>

                            <div class="js-item-card-mr" style="display: {{ $mr_style }};">

                                <div class="mrc-line">
                                    <div class="text-before">Цена МРЦ</div>
                                    <div class="price-mrc">{{ price($price_mr) }} руб</div>
                                    <div class="text-after">с НДС</div>
                                </div>

                            </div>

                            <div class="js-item-card-opt" style="display: {{ $opt_style }};">

                                <div class="opt-line">
                                    <div class="text-before">Цена опт</div>
                                    <div class="price-opt">
                                    <span @if($discount_values->count() == 1 && $discount_values->first()->condition == 1) class="through" @endif>
                                        {{ price($price) }} руб
                                    </span>
                                        @foreach($discount_values as $discountValue)
                                            @php $discountedPrice = price(percent($item_card->price_rub, $discountValue->value)); @endphp
                                            @if($discount_values->count() == 1)
                                                <div class="">{{ $discountedPrice }} руб</div>
                                            @else
                                                <div class="action-str">от {{$discountValue->condition}} шт. {{$discountedPrice}} руб.</div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="text-after">с НДС</div>
                                </div>

                            </div>

                            @if($discount_values->count() > 0 || $item_card->spec_price == 1)
                                <div class="sticker-line">
                                    <div class="action-wrapper">
                                        @if($discount_values->count() > 0)
                                            <div class="action-sing">Акция</div>
                                        @elseif($item_card->spec_price == 1)
                                            <div class="action-sing spec">Спецпредложение</div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($item_card->amount > 0)

                                <div class="pcs-line">

                                    <div class="text-before">
                                        Кол-во:
                                    </div>

                                    <div class="pcs-controll">

                                        <livewire:count :itemId1c="$item_card->id_1c" :cart="$carts->where('item_1c_id', $item_card->id_1c)->first()" />

{{--                                        <div class="button js-icard-remove-from-cart minus">--}}
{{--                                            ---}}
{{--                                        </div>--}}

{{--                                        @php--}}

{{--                                            // если есть в корзине, отображаем количество--}}
{{--                                            if(isset($in_cart[$item_card->id_1c])) {--}}
{{--                                                $count_in_cart = $in_cart[$item_card->id_1c];--}}
{{--                                            } else {--}}
{{--                                                $count_in_cart = 0;--}}
{{--                                            }--}}

{{--                                        @endphp--}}

{{--                                        <input--}}
{{--                                            type="number"--}}
{{--                                            name="item_count"--}}
{{--                                            class="table-price-input js-count-input"--}}
{{--                                            value="{{ $count_in_cart }}"--}}
{{--                                            data-step="{{ $packaging }}"--}}
{{--                                            data-id_1с="{{ $item_card->id_1c }}"--}}
{{--                                            data-rout_name="{{ \Request::route()->getName() }}"--}}
{{--                                            onfocus="this.removeAttribute('readonly');"--}}
{{--                                            readonly--}}
{{--                                            autocomplete="off"--}}
{{--                                        >--}}

{{--                                        <div class="button js-icard-add-to-cart plus">--}}
{{--                                            +--}}
{{--                                        </div>--}}

                                    </div>

                                    <div class="text-after">
                                        шт.
                                    </div>

                                </div>

                            @endif

                            @if($packaging > 1)

                                <div
                                    class="packaging-line js-icard-add-to-cart-package js-icard-packaging plus"
                                    data-block_id="{{ $packaging }}"
                                    data-item_id="{{ $item_card->id }}"
                                    title="Жми, чтобы добавить {{ $packaging }} шт."
                                >
                                    В упаковке {{ $packaging }} шт.
                                </div>

                            @endif

                            @php
                                $item1cId = $item_card->id_1c; // для отправки письма
                            @endphp

                            <div class="link-line js-demping">
                                Пожаловаться на демпинг
                                <div style="font-size: 15px; padding: 10px 10px 0; line-height: 1.4;">
                                    {{ $item_card->name }}
                                </div>
                            </div>

                            <br>

                            <div class="link-line last js-discount">
                                Хочу дешевле
                                <div style="font-size: 15px; padding: 10px 10px 0; line-height: 1.4;">
                                    {{ $item_card->name }}
                                </div>
                            </div>

                        @endif

                        @if($item_card->schemes && $schemes->count())

                            <div class="spares-link" id="js-spares">
                                <div class="button">
                                    <div class="svg">
                                        @include('svg.gear_ico')
                                    </div>
                                    <div class="button-name">
                                        Запчасти
                                    </div>
                                </div>
                            </div>

                        @endif

                    </div>

                    @if($item_card->equipment)

                        <div class="item-page_equipment-block">
                            <div class="header">
                                Комплектация
                            </div>
                            <div class="text">
                                {!! $item_card->equipment !!}
                            </div>
                        </div>

                    @endif

                    @if(profile()->isService())

                        <div class="item-page_repair-block">
                            <div class="header">
                                Ремонты:
                            </div>
                            <div class="new-repair-form">
                                <form method="post" action="{{ asset('new-repair') }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="item_id_1c" value="{{ $item_card->id_1c }}">
                                    <input type="text" name="repair_name" required>
                                    <button type="submit">Создать</button>
                                </form>

                            </div>
                        </div>

                    @endif

                </div>
            </section>


            <section class="item-page_products-block" id="js-spares-view" data-spares-link="{{ $spares_link }}">

                <div class="buttons">

                    <div class="clap clap-about active">
                        Подробнее о товаре
                    </div>

                    @if($item_card->schemes && $schemes->count())

                        <div class="clap clap-spare">
                            Запчасти
                        </div>

                    @endif

                    {{--                 @if($analogs->count())

                                        <div class="clap clap-analog">
                                            Аналоги
                                        </div>

                                    @endif --}}

                    @if($buy_with_cat->count() || $buy_with->count())

                        <div class="clap clap-buy-with">
                            С этим товаром покупают
                        </div>

                    @endif


                    @if($services->count())

                        <div class="clap clap-services">
                            Работы
                        </div>

                    @endif

                    {{--                 @if(isset($spare_items) && $spare_items->count())

                                        <div class="clap clap-comes-to">
                                            Подходит к...
                                        </div>

                                    @endif --}}

                </div>

                <div class="item-page_down-block js-down-block block-about active">

                    @include('item.about', [
                            'opt_style' => $opt_style,
                            'purcent_style' => $purcent_style,
                            'mr_style' => $mr_style,
                        ])

                </div>

                @if($item_card->schemes && $schemes->count())

                    <div class="item-page_down-block js-down-block block-spare">

                        @include('item.spares', [
                                'opt_style' => $opt_style,
                                'purcent_style' => $purcent_style,
                                'mr_style' => $mr_style,
                            ])

                    </div>

                @endif

                {{--             @if($analogs->count())

                                <div class="item-page_down-block js-down-block block-analog">

                                    @include('catalog.snippets.item_card_analogs', [
                                            'opt_style' => $opt_style,
                                            'purcent_style' => $purcent_style,
                                            'mr_style' => $mr_style,
                                        ])

                                </div>

                            @endif --}}

                @if($buy_with_cat->count() || $buy_with->count())

                    <div class="item-page_down-block js-down-block block-buy-with">

                        @include('item.buy_with', [
                                'opt_style' => $opt_style,
                                'purcent_style' => $purcent_style,
                                'mr_style' => $mr_style,
                            ])

                    </div>

                @endif

                @if($services->count())

                    <div class="item-page_down-block js-down-block block-services">

                        @include('catalogue.item_card_services', [
                                'opt_style' => $opt_style,
                                'purcent_style' => $purcent_style,
                                'mr_style' => $mr_style,
                            ])

                    </div>

                @endif

                {{--             @if(isset($spare_items) && $spare_items->count())

                                <div class="item-page_down-block js-down-block block-comes-to">

                                    @include('catalog.snippets.item_card_comes_to')

                                </div>

                            @endif
                 --}}

            </section>


        </div>
    </div>

    {{-- Всплывающее окно видео Youtube --}}
    @include('includes.popups.youtube')

@endsection

@section('css')
    @parent

    <link rel="stylesheet" href="{{ asset('assets/css/jquery.fancybox.css') }}">

@endsection

@section('scripts')
    @parent

    <script type="text/javascript" src="{{ asset('assets/js/blowup.js').'?v='.config('constants.version') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/item_card_img.js').'?v='.config('constants.version') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/js/jquery.fancybox.js') }}"></script>
    <script>
        $(function () {
            $("[data-fancybox]").fancybox({loop: true});
        });
    </script>

    <script type="text/javascript" src="{{ asset('assets/js/youtube.js').'?v='.config('constants.version') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/item_card_line_togglers.js').'?v='.config('constants.version') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/togglers.js').'?v='.config('constants.version') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/catalog_items_toggler.js').'?v='.config('constants.version') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/picture_zoom.js').'?v='.config('constants.version') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/filters_line.js').'?v='.config('constants.version') }}"> </script>
    {{-- <script type="text/javascript" src="{{ asset('assets/js/category_handler.js').'?v='.config('constants.version') }}"> </script> --}}

@endsection
