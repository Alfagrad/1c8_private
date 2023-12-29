@extends('layouts.app')

@section('content')

@php 
    global $i;
    $GLOBALS['i'] = 0;
    $is_analog_line = 0; // метка для вывода аналогов
@endphp

@php
    // если есть markup, учитываем при отображении цен
    if($data_markup) {

        if($data_markup) $markup = $data_markup / 100 + 1;
            else $markup = 1;

        // определяем максимально возможный для BYN (для уцененных товаров не учитываем)
        if($item_card->bel_price != 0 && $item_card->{'1c_category_id'} != '3149') $maxMarkupBYN = $item_card->price_mr_bel/$item_card->bel_price;
            else $maxMarkupBYN = 1;
        // если получается больше максимального, ставим максимально возможный
        if($markup > $maxMarkupBYN) $markupBYN = $maxMarkupBYN;
            else $markupBYN = $markup;
    } else {
        $markupBYN = 1;
        $maxMarkupBYN = '';
    }
    // ***********************************************

    // формируем строку скидок для акционных товаров
    if($item_card->discounted_rub) {
        // делим скидки
        $discounts = explode(';', $item_card->discounted_rub);
        // убираем пустые значения
        $discounts = array_diff($discounts, array(''));
        // отделяем количество от цены
        foreach ($discounts as $pLD) {
            if ($pLD) {
                list($count, $price) = explode('-', $pLD);
                $listDiscount[] = [
                    'count' => $count,
                    'price' => $price
                ];
            }
        }
        // формируем строку (с использованием markup)
        $actionString = '';
        $actionString_2 = '';
        foreach ($listDiscount as $price) {
            $actionString .= ' от ' . $price['count'] . ' шт ' . number_format(ceil((float)$price['price'] * $markupBYN * 100) / 100, 2) . ' руб, ';
            $actionString_2 .= ' от ' . $price['count'] . ' шт ' . number_format(ceil((float)$price['price'] * $markupBYN * 100) / 100, 2) . ' руб<br>';
        }
        if ($actionString) {
            $actionString = substr($actionString, 0, -2);
            $actionString_2 = substr($actionString_2, 0, -4);
        }

        if($item_card->is_action) {
            // расчитываем % скидки для акции
            $end = end($listDiscount);
            if($item_card->bel_price != 0) $maxPrs = round((1 - ((float)$end['price'] * $markupBYN) / ($item_card->bel_price * $markupBYN)) * 100);
                else $maxPrs = 0;
            $discountString = "Акция - ".$maxPrs."%";
        }
    }
    // ***********************************************

@endphp

    <body onload="
    @if($item_card->getSchemeParent()->count())
            spares_link({{ $item_card->id }}, '{{ $token }}', '{{ csrf_token() }}')
    @endif
            ">
    <div class="b-wrapper p-item">

        @include('general.header')
        @include('general.nav')

        <section class="s-product">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        @if($breadcrumbs != null)
                            @foreach($breadcrumbs as $breadcrumb)
                                <a href="{{asset('catalogue/'.$breadcrumb['id'])}}"
                                   class="kroshka">{{$breadcrumb['name']}}</a>
                                @if(!$loop->last)
                                    →

                                @else
                                    <input type="hidden" class="categoryId" value="{{$breadcrumb['id']}}">
                                @endif

                            @endforeach
                        @endif
                    </div>

                </div>
                <div class="wrapper w-product-main-info">

                    <div class="wrapper w-name">
                        <h1>{{$item_card->name}} Артикул: {{$item_card->vendor_code}}

                            @if($item_card->discounted_rub)

                            <span style="color:red">{{ $actionString }}</span>

                            @endif

                        </h1>
                    </div>

                    <div class="w-product-content">
                        <div class="w-product-slider" style="">
                            <div class="top">

                                @if($item_card->is_new_item == 1)
                                <div class="hovered-product_new-item-sign big-sign">NEW</div>
                                @endif

                                <ul class="item-slider">
                                    @forelse($item_card->images as $image)
                                        <li>
                                            <a class="grouped_elements" rel="group1"
                                               href=" {{asset($imageResize->resize($image->path_image))}}">
                                                <img src=" {{asset($imageResize->resize($image->path_image))}}" alt=""

                                                     class="imageResize">
                                            </a>
                                        </li>
                                    @empty
                                        <li>
                                            <a class="grouped_elements" rel="group1"
                                               href=" {{asset($imageResize->resize(('500100')))}}">
                                                <img src=" {{asset($imageResize->resize(('500100')))}}" alt="">
                                            </a>
                                        </li>
                                    @endforelse

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

                                        <li>
                                            <iframe width="400" height="400" src="https://www.youtube.com/embed/{{ $youtube_code }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </li>

                                        @endforeach

                                    @endif

                                </ul>
                            </div> <?php $cnt = 0?>
                            <div class="bottom item-slider-pager">
                                <div class="for-slider-pager" style="
                                        display: inline-block;">

                                    @foreach($item_card->images as $image)

                                        <div class="item">
                                            <div class="w-image">
                                                <div class="image">
                                                    <a data-slide-index="{{$cnt}}" href="">
                                                        <img src="{{asset($imageResize->resize($image->path_image))}}"
                                                             alt="">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <?php $cnt++?>

                                    @endforeach

                                    @if(trim($item_card->youtube))

                                        @foreach($youtube_array as $youtube_link)

                                        <div class="item">
                                            <div class="w-image">
                                                <div class="image">
                                                    <a data-slide-index="{{$cnt}}" href="">
                                                        <div class="youtube"></div>
                                                        <img style="position:relative;"
                                                             src="@if($item_card->images->count())
                                                             {{ asset($imageResize->resize($item_card->images[0]->path_image)) }}
                                                             @else
                                                             {{ asset($imageResize->resize(('500100'))) }}
                                                             @endif"
                                                        >
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $cnt++?>

                                        @endforeach

                                    @endif
                                </div>

                                @if($item_card->guide_file)
                                <div class="item" style="margin-left: 10px">
                                    <div class="w-image">
                                        <div class="image">
                                            <a href="{{ asset('storage/item-images/manuals/'.$item_card->guide_file) }}" style="font-size: 16px;" target="_blank" title="скачать инструкцию по эксплуатации">
                                                <img style="position:relative;" src="{{asset('assets/img/doc.png')}}"
                                                     alt="">
                                                <div style="margin-top: 5px">Инструкция</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif

{{--                                 @if($item_card->certificate_file AND ($item_card->certificate_exp == '0000-00-00' OR $item_card->certificate_exp >= date('Y-m-d')))
                                <div class="item">
                                    <div class="w-image">
                                        <div class="image">
                                            <a href="{{ asset('storage/item-images/certificates/'.$item_card->certificate_file) }}" style="font-size: 16px;" target="_blank" title="Скачать сертификат">
                                                <img style="position:relative;" src="{{asset('assets/img/pdf.png')}}"
                                                     alt="">
                                                <div style="margin-top: 5px">Сертификат</div>

                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif --}}

                                <div class="item" style="margin-left: 10px">
                                    <div class="w-image">
                                        <div class="image">
                                            <a href="{{ asset('/pricetag/form/'.$item_card->{'1c_id'}) }}" style="font-size: 16px;" target="_blank" title="Подготовить и скачать ценник">
                                                <img style="position:relative;" src="{{asset('assets/img/pr_tag.png')}}"
                                                     alt="">
                                                <div style="margin-top: 5px">Ценник</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="w-product-info">
                            <div class="wrapper">

                                <div class="w-graybgr">
                                    <div class="wrapper flex-line">
                                        <div class="product-code">
                                            Код: {{$item_card->code}}
                                        </div>

                                        @if($item_card->count > 0)

                                            <div class="icon _yes _verylot _hidden-info">В наличии
                                                
                                            @if($item_card->count > 10)

                                                @if(Auth::user()->role_id == '5' || Auth::user()->role_id == '1')
                                                <strong>{{ $item_card->count }} шт.</strong>
                                                @endif
                                                <div class="cursor-hover-info">Доступно более 10шт</div>

                                            @elseif($item_card->count >= 5 and $item_card->count <= 10 )

                                                @if(Auth::user()->role_id == '5' || Auth::user()->role_id == '1')
                                                <strong>{{ $item_card->count }} шт.</strong>
                                                @endif
                                                <div class="cursor-hover-info">на складе {{$item_card->count}}шт</div>

                                            @else

                                                @if(Auth::user()->role_id == '5' || Auth::user()->role_id == '1')
                                                <strong>{{ $item_card->count }} шт.</strong>
                                                @endif
                                                <div class="cursor-hover-info">на складе {{$item_card->count}} шт</div>

                                            @endif

                                            </div>

                                        @elseif($item_card->count_type == 2)

                                            <div class="icon _no _reserved _hidden-info">Резерв
                                                <div class="cursor-hover-info">Звоните</div>
                                            </div>

                                        @elseif($item_card->count_type == 3)

                                            <div class="icon _no _hidden-info">{{$item_card->count_text}}
                                                <div class="cursor-hover-info">Поступит {{$item_card->count_text}}</div>
                                            </div>

                                        @elseif($item_card->count_type == 4)

                                            <div class="icon _no _reserved _hidden-info">Нет
                                                <div class="cursor-hover-info">Нет на складе</div>
                                            </div>

                                        @endif

                                    </div>

                                    <div class="wrapper">
                                        <div class="wrapper center flex-line">
                                            Цена МРЦ
                                            <div class="product-price-red">
                                                {{$item_card->price_mr_bel}} руб
                                            </div>
                                            с НДС

                                        </div>
                                    </div>

                                    <div class="flex-line">
                                        Цена опт
                                        <div class="product-price">
                                            <span @if($item_card->discounted == 1) class="through" @endif>
                                                {{ number_format(ceil($item_card->bel_price * $markupBYN * 100) / 100, 2) }} руб
                                            </span>

                                            @if($item_card->discounted > 1)
                                            <div class="product-price_action">{!! $actionString_2 !!}</div>
                                            @endif

                                            @if($item_card->discounted == 1)
                                                <div class="product-price_action2">{{ number_format( ceil($item_card->priceMinDiscountByn * $markupBYN * 100) / 100,  2) }} руб</div>
                                            @endif

                                        </div>
                                        с НДС
                                    </div>

                                    @if($item_card->is_action != 0 || $item_card->mini_text != '')

                                    <div class="flex-line">

                                        @if($item_card->discounted_rub && $item_card->is_action)

                                            <div class="sticker _sale _hidden-info inline-roll big" rel="tipsy">
                                                {{ $discountString }}

                                            </div>

                                        @endif

                                        @if($item_card->mini_text != '')
                                            <div class="roll inline-roll _sale _hidden-info big">%
                                                <div class="cursor-hover-info">
                                                    {{ $item_card->mini_text }}
                                                </div>
                                            </div>
                                        @endif

                                    </div>

                                    @endif

{{--                                     <div class="wrapper center">
                                        <div class="td-sale-colored">
                                            @if(isset($item_card->gift->name))
                                                <div class="sticker _gift _hidden-info">Подарок
                                                    <div class="cursor-hover-info">{{$item_card->gift->name}}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div> --}}

                                    <div class="wrapper w-pcs">

                                    @if($item_card->count > 0 && $item_card->in_archive != 1)

                                        <span class="text">Кол-во:</span>

                                        <div class="pcs-controll _minus js-item-remove-from-cart">-</div>

                                        <input 
                                            type="number"
                                            name="item_count"
                                            data-item_id="{{$item_card->id}}"
                                            data-1cid="{{($item_card->{'1c_id'})}}"
                                            id="item-{{$item_card->id}}"
                                            class="table-price-input"
                                            value="@if(isset($idToCart[$item_card->id])){{ $idToCart[$item_card->id] }}@else{{ '0' }}@endif"
                                            step="{{ $item_card->packaging }}"
                                            {{-- step_index = "0" --}}
                                            onfocus="this.removeAttribute('readonly');" readonly autocomplete="off"
                                        >

                                        <div class="pcs-controll _plus js-item-add-to-cart">+</div> шт.


                                        @if($item_card->packaging > 1)
                                        <div class="_hidden-info js-item-add-to-cart-package" data-block_id="{{$item_card->packaging}}" onclick="update({{$item_card->id}}, {{$item_card->packaging}})">
                                            В упаковке {{$item_card->packaging}} шт.
                                            <div class="cursor-hover-info-bottom ">Жми, чтобы добавить  {{$item_card->packaging}} шт.</div>
                                        </div>
                                        @endif

                                    @endif

                                    </div>
                                    <div class="wrapper product-pop-links">
                                        <a href="" class="js-b-demping">Пожаловаться на демпинг</a><br>
                                        <a href="" class="js-b-discount" data-item_id="{{$item_card->id}}"
                                           data-item_name="{{$item_card->name}}">Хочу дешевле</a>
                                    </div>
                                    <div class="btn-wrapper">
                                        @if($item_card->getSchemeParent()->count())
                                            <button class="spares-modal-btn spares-modal-btn-full js-view-spares"
                                                    data-crsf="{{ csrf_token() }}" data-id="{{ $item_card->id }}"
                                                    data-token="{{ $token }}" onclick="spares_down()" type="button">
                                                <svg role="img" aria-hidden="true" width="16" height="16"
                                                     viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"
                                                     fill="currentColor">
                                                    <path d="M15.603 6.512l-1.428-.172c-.146-.545-.36-1.059-.635-1.537l.888-1.128c.136-.173.12-.442-.037-.598l-1.471-1.472c-.155-.156-.424-.172-.598-.036l-1.13.889c-.476-.275-.99-.489-1.533-.634l-.171-1.428c-.026-.218-.228-.397-.448-.397h-2.081c-.22 0-.422.179-.447.397l-.172 1.428c-.544.145-1.059.36-1.535.634l-1.13-.888c-.173-.136-.442-.12-.597.036l-1.472 1.473c-.155.155-.171.424-.035.598l.887 1.129c-.275.476-.489.991-.634 1.536l-1.427.171c-.218.026-.397.227-.397.448v2.081c0 .22.179.42.397.447l1.428.172c.146.543.359 1.057.634 1.533l-.887 1.13c-.135.174-.12.442.036.598l1.469 1.473c.156.155.424.172.597.037l1.13-.889c.476.275.991.489 1.535.634l.172 1.427c.026.219.227.397.447.397h2.081c.221 0 .422-.178.447-.397l.172-1.427c.545-.146 1.059-.36 1.535-.634l1.13.888c.173.136.442.12.597-.036l1.471-1.473c.156-.155.172-.424.036-.598l-.888-1.129c.276-.476.489-.991.635-1.534l1.427-.172c.219-.026.397-.226.397-.447v-2.081c.001-.221-.177-.422-.397-.448zm-7.603 5.488c-2.209 0-4-1.791-4-4s1.791-4 4-4 4 1.791 4 4-1.79 4-4 4z"/>
                                                </svg>
                                                Запчасти
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($item_card->equipment)
                            <div class="wrapper">
                                <div class="w-graybgr">
                                    <div class="wrapper">
                                        <h3 style="margin-bottom: -10px">Комплектация</h3>
                                        <div style="white-space:pre-line">
                                            {!! $item_card->equipment!!}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>


                <div class="wrapper w-product-claps" id="w-product-claps">

                    <div class="wrapper w-claps">
                        <a href="javascript: void(0);" class="clap _active" role="button">Подробнее о товаре</a>

                        @if($item_card->getSchemeParent()->count())

                        <a href="javascript: void(0);" class="clap js-view-spares" id="spares-clap-link"
                            data-crsf="{{ csrf_token() }}" data-id="{{ $item_card->id }}" data-token="{{ $token }}"
                            onclick="" role="button">Запчасти</a>

                        @endif

                        @if($analog > 0)

                        <a href="javascript: void(0);" class="clap" role="button">Аналоги</a>

                        @endif

                        @if($buyWithCategory->count() || $buyWith->count())

                        <a href="javascript: void(0);" class="clap" role="button">C этим товаром покупают</a>

                        @endif

                    </div>


                    <!-- Содержимое табов -->

                    <div class="wrapper clap-content fade in _active"
                         style="    padding: 0px; background-color:transparent;">
                        <div class="wrapper">
                            <table width="100%" cellspacing="0" cellpadding="4" style="    margin: 0px 0;">
                                <thead style=" width: 100%;">
                                    <tr style="width: 100vw;">
                                        <td class="td-article clap table-item" style=" background-color: #F5F5F5">
                                            <b>Характеристики</b>
                                        </td>
                                        <td class="td-name clap table-item" style=" background-color: #E3E3E3">
                                            <b>Краткое описание</b>
                                        </td>
                                        <td class="td-more clap table-item" style=" background-color: #F5F5F5">
                                            <b>Преимущества</b>
                                        </td>
                                        <td class="td-sale clap table-item" style="  background-color: #E3E3E3">
                                            <b>Дополнительные данные по товару и продавцу</b>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody style="width: 100%;">
                                    <td class="td-article"
                                        style="vertical-align:top; padding: 0 20px 0 20px; background-color: #F5F5F5">
                                        <article>

                                            <p>{{$item_card->description}} @if($item_card->description) <br> @endif
                                                @foreach($item_card->charValues as $charValues)
                                                    @if($charValues->characteristic)
                                                        <b>{{$charValues->characteristic->name}}
                                                            : </b>{{$charValues->value}} {{$charValues->characteristic->unit}}
                                                        <br>
                                                    @endif
                                                @endforeach

                                        </article>
                                    </td>
                                    <td class="td-name"
                                        style="vertical-align:top; padding:  0 20px 0 20px; background-color: #E3E3E3"><p
                                                style="padding: 0 40px 0 0;  ">{{$item_card->more_about}}</p></td>
                                    <td class="td-more" style="vertical-align:top;  background-color: #F5F5F5; padding:  0 20px 0 20px;">
                                        <p style="white-space:pre-line">{!!  $item_card->content!!}</p>
                                    </td>
                                    <td class="td-sale"
                                        style="vertical-align:top; padding:  0 20px 0 20px;  background-color: #E3E3E3 ">
                                        <div style="padding:  10px 10px 0 0">


                                            @if($item_card->brand)
                                                <div>
                                                    Производитель: {!! $item_card->brand !!}
                                                </div>
                                            @endif
                                            @if( $item_card->apply)
                                                <div>
                                                    Назначение: {!!  $item_card->apply !!}
                                                </div>
                                            @endif

                                            @if($item_card->shelf_life)
                                                <div>
                                                    Срок службы: {!!  $item_card->shelf_life !!}
                                                </div>
                                            @endif
                                            @if($item_card->country)
                                                <div>
                                                    Страна изготовления: {!!  $item_card->country !!}
                                                </div>
                                            @endif
    {{--                                         @if( $item_card->brand)
                                                <div>
                                                    Бренд: {!!  $item_card->brand !!}
                                                </div>
                                            @endif
     --}}                                        @if($item_card->importer)
                                                <div>
                                                    Импортер: {!!  $item_card->importer  !!}
                                                </div>
                                            @endif
                                            @if($item_card->barcode)
                                                <div>
                                                    Штрих-код: {!!  $item_card->barcode !!}
                                                </div>
                                            @endif
                                            @if($item_card->certificate)
                                                <div>
                                                    Сертификат: {!!  $item_card->certificate ? : 'не указано'!!}
                                                </div>
                                            @endif

                                            @if ($item_card->depth &&  $item_card->width && $item_card->height)

                                            <div>
                                                Габариты упаковки:
                                                {!!  $item_card->depth !!}х{!!  $item_card->width !!}х{!!  $item_card->height !!}
                                                мм.
                                            </div>

                                            @endif

                                            @if($item_card->weight )

                                            <div>
                                                Вес с упаковкой:
                                                {!!  $item_card->weight !!} кг.
                                            </div>

                                            @endif

                                            @if($item_card->guarantee_period )

                                            <div>
                                                Гарантийный срок:
                                                {{  $item_card->guarantee_period }} мес.
                                            </div>

                                            @endif
                                        </div>
                                    </td>

                                </tbody>
                            </table>

                        </div>


                        <div class="wrapper items-table catalog-table">

                            @if($discountedItem->count())
                                <h3>Уцененные</h3>
                                <div class="w-table">
                                    <div class="table-head">
                                        <table>
                                            <thead>
                                            <tr>
                                                <td class="td-image">
                                                    <div class="toggler-button"></div>
                                                </td>

                                                <td class="td-article">
                                                </td>
                                                <td class="td-name">Наименование</td>
                                                <td class="td-sale"></td>

                                                <td class="td-price _{{$currency}}">Цена
                                                    <div class="content-show-usd">USD с НДС</div>
                                                    <div class="content-show-byn">BYN с НДС</div>
                                                </td>
                                                <td class="td-price _{{$currency}}">
                                                    {{-- <div class="content-show-usd">Наценка дилера, %</div>
                                                    <div class="content-show-byn">Наценка дилера, %</div> --}}
                                                </td>
                                                <td class="td-price _mrp _{{$currency}}">МРЦ
                                                    <div class="content-show-usd">USD</div>
                                                    <div class="content-show-byn">BYN</div>
                                                </td>
                                                <td class="td-take-discount"></td>
                                                <td class="td-pcs"></td>
                                                <td class="td-valible"></td>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                   <div class="table-body _toggled" style="display: block;">
                                        <table>
                                            <tbody>
                                            @foreach($discountedItem as $item)
                                                @if($item->count > 0)

@include('catalog.snippets.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('catalog.snippets.tr_partial_analog')
                                                @endif
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($buyForget->count())

                                @php($buyForgetCount = 0)
                                @foreach($buyForget as $buy)
                                    @if($buy->count > 0)
                                        @php($buyForgetCount++)
                                    @endif
                                @endforeach

                                <h3>Не забудь купить</h3>
                                <div class="w-table">
                                    <div class="table-head dropdown">
                                        <table>
                                            <thead>
                                            <tr>
                                                <td class="td-image">
                                                    @if($buyForgetCount > 4)
                                                    <div 
                                                        data-category="forget"
                                                        class="toggler-buttonfor-cart"
                                                        title="Показать/скрыть все товары"
                                                        id="forget" 
                                                    ></div>
                                                    <span 
                                                        class="toggler-buttonfor-cart-2" 
                                                        data-category="forget"
                                                        title="Показать/скрыть все товары"
                                                    >
                                                        Показать все ({{($buyForgetCount)}})
                                                    </span>
                                                    @endif
                                                </td>
                                                <td class="td-name">Наименование</td>
                                                <td class="td-sale"></td>

                                                <td class="td-price _{{$currency}}">Цена
                                                    <div class="content-show-usd">USD с НДС</div>
                                                    <div class="content-show-byn">BYN с НДС</div>
                                                </td>
                                                <td class="td-price _{{$currency}}">
                                                    <div class="content-show-usd">Наценка дилера, %</div>
                                                    <div class="content-show-byn">Наценка дилера, %</div>

                                                </td>
                                                <td class="td-price _mrp _{{$currency}}">МРЦ
                                                    <div class="content-show-usd">USD</div>
                                                    <div class="content-show-byn">BYN</div>
                                                </td>
                                                <td class="td-take-discount"></td>
                                                <td class="td-pcs"></td>
                                                <td class="td-valible"></td>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>

                                    <div class="table-body _toggled w-cart-info-form" style="display: block;">
                                        <table>
                                            <tbody>
                                            @php $key = 0; @endphp 

                                            @foreach($buyForget as $item)

                                                @if($item->count > 0)
                                                    @php $key++; @endphp

@include('catalog.snippets.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('catalog.snippets.tr_partial_analog')
                                                @endif
                                            @endforeach

                                            @php
                                                unset($buyForget); // удаляем, чтобы срабатывал "С этим товаром покупают" 
                                            @endphp

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            @endif
                        </div>
                    </div>

                    @if($item_card->getSchemeParent()->count())
                        <div class="wrapper clap-content fade" id="spares-clap">
                            <div class="content preloader">
                                <div class="w-circle">
                                    <div class="circle"></div>
                                    <div class="circle1"></div>
                                    <div class="circle2"></div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($analog > 0)
                        <div class="wrapper clap-content fade">
                            <div class="wrapper items-table catalog-table">
                                <h3>Аналоги</h3>
                                <div class="product-block">

                                    <div class="product-block-content items-table">
                                        <div class="product-block-spares">
{{--                                             <div class="table-head">
                                                <div class="s-main-navigation">
                                                    <div class="wrapper w-main-navigation">
                                                        <div class="wrapper w-filters-table">
                                                            <div class="input">
                                                                <a href="" class="button js-b-usd ">USD</a>
                                                                <a href="" class="button js-b-byn _active">BYN</a>
                                                            </div>
                                                            <div class="input">
                                                                <a href="" class="button js-b-mrp">МРЦ</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}

                                            @foreach($items as $item)
                                                @php
                                                    $scheme_parts = $item;
                                                    $item = $item->getItem;
                                                @endphp
                                            @endforeach

                                            @if($item->count > 0)
                                                <div class="table-wrapper">
                                                    <div class="table-head">
                                                        <h5>В наличии</h5>
                                                        <table>
                                                            <thead>
                                                            <tr>
                                                                <td class="td-image"></td>
                                                                <td class="td-article">Код</td>
                                                                <td class="td-name">Наименование</td>
                                                                <td class="td-sale"></td>
                                                                <td class="td-price _{{$currency}}">Цена
                                                                    <div class="content-show-usd">USD с НДС</div>
                                                                    <div class="content-show-byn">BYN с НДС</div>
                                                                </td>
                                                                <td class="td-price _{{$currency}}">
                                                                    <div class="content-show-usd">Наценка дилера, %</div>
                                                                    <div class="content-show-byn">Наценка дилера, %</div>

                                                                </td>
                                                                <td class="td-price _mrp _{{$currency}}">МРЦ
                                                                    <div class="content-show-usd">USD</div>
                                                                    <div class="content-show-byn">BYN</div>
                                                                </td>
                                                                <td class="td-take-discount"></td>
                                                                <td class="td-pcs"></td>
                                                                <td class="td-valible"></td>
                                                            </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                    <div class="table-body">
                                                        <table>
                                                            <tbody>

                                                            @foreach($items as $item)

                                                                @php
                                                                    // $scheme_parts = $item;
                                                                    $item = $item->getItem;
                                                                @endphp

                                                                @if($item->count > 0)

@include('catalog.snippets.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('catalog.snippets.tr_partial_analog')
                                                                @endif

                                                            @endforeach

                                                            </tbody>
                                                        </table>

                                                    </div>

                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif

                    @if($buyWithCategory->count() || $buyWith->count())

                    <div class="wrapper clap-content fade">
                        <div class="wrapper items-table catalog-table">
                            <h3>С этим товаром покупают</h3>

                            {{-- Вы водим товары из категорий --}}
                            @if($buyWithCategory->count())

                                @foreach($buyWithCategory as $buyCat)

                                @if(!$buyCat->items->count())
                                    @continue
                                @endif

                                @php($byWidthCount = 0)

                                @foreach($buyCat->items as $buy)

                                    @if($buy->count > 0)
                                        @php($byWidthCount++)
                                    @endif

                                @endforeach

                                <div class="w-table">
                                    <div class="table-head dropdown">
                                        <table>
                                            <thead>
                                            <tr>
                                                <td class="td-image">
                                                    @if($byWidthCount > 4)
                                                    <div data-category="{{ $buyCat->id }}"
                                                        title="Показать/скрыть все товары"
                                                        class="toggler-buttonfor-cart"
                                                        id="{{ $buyCat->id }}">
                                                    </div>
                                                    <span 
                                                        class="toggler-buttonfor-cart-2" 
                                                        data-category="{{ $buyCat->id }}"
                                                        title="Показать/скрыть все товары"
                                                    >
                                                        Показать все ({{($byWidthCount)}})
                                                    </span>
                                                    @endif
                                                </td>
                                                <td class="td-article"></td>
                                                <td class="td-name">{{$buyCat->name}}</td>
                                                <td class="td-price _{{$currency}}">Цена
                                                    <div class="content-show-usd">USD с НДС</div>
                                                    <div class="content-show-byn">BYN с НДС</div>
                                                </td>
                                                <td class="td-price _{{$currency}}">
                                                    <div class="content-show-usd">Наценка дилера, %</div>
                                                    <div class="content-show-byn">Наценка дилера, %</div>

                                                </td>
                                                <td class="td-price _mrp _{{$currency}}">МРЦ
                                                    <div class="content-show-usd">USD</div>
                                                    <div class="content-show-byn">BYN</div>
                                                </td>
                                                <td class="td-take-discount"></td>
                                                <td class="td-pcs"></td>
                                                <td class="td-valible"></td>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>

                                    <div class="table-body _toggled w-cart-info-form" style="display: block;">
                                        <table>
                                            <tbody>

                                            @php($key = 0)

                                            @foreach($buyCat->items as $item)

                                                @if($item->count > 0)
                                                    @php($key++)

@include('catalog.snippets.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('catalog.snippets.tr_partial_analog')
                                                @endif
                                            @endforeach

                                           </tbody>
                                        </table>
                                    </div>

                                </div>

                                @endforeach
                            @endif

                            {{-- Вы водим товары без категорий --}}
                            @if($buyWith->count())

                                @php
                                    unset($buyWithCategory); // удаляем, чтобы сработала раскрывашка ("+")
                                    $byWidthCount = 0;
                                @endphp

                                @foreach($buyWith as $buy)

                                    @if($buy->count > 0)
                                        @php($byWidthCount++)
                                    @endif

                                @endforeach

                                <div class="w-table">
                                    <div class="table-head dropdown">
                                        <table>
                                            <thead>
                                            <tr>
                                                <td class="td-image">
                                                    @if($byWidthCount > 4)
                                                    <div 
                                                        data-category="forget"
                                                        class="toggler-buttonfor-cart"
                                                        title="Показать/скрыть все товары"
                                                        id="forget" 
                                                    ></div>
                                                    <span 
                                                        class="toggler-buttonfor-cart-2" 
                                                        data-category="forget"
                                                        title="Показать/скрыть все товары"
                                                    >
                                                        Показать все ({{ $byWidthCount }})
                                                    </span>
                                                    @endif
                                                </td>
                                                <td class="td-article"></td>
                                                <td class="td-name">Товары</td>
                                                <td class="td-price _{{$currency}}">Цена
                                                    <div class="content-show-usd">USD с НДС</div>
                                                    <div class="content-show-byn">BYN с НДС</div>
                                                </td>
                                                <td class="td-price _{{$currency}}">
                                                    <div class="content-show-usd">Наценка дилера, %</div>
                                                    <div class="content-show-byn">Наценка дилера, %</div>

                                                </td>
                                                <td class="td-price _mrp _{{$currency}}">МРЦ
                                                    <div class="content-show-usd">USD</div>
                                                    <div class="content-show-byn">BYN</div>
                                                </td>
                                                <td class="td-take-discount"></td>
                                                <td class="td-pcs"></td>
                                                <td class="td-valible"></td>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>

                                    <div class="table-body _toggled w-cart-info-form" style="display: block;">
                                        <table>
                                            <tbody>

                                            @php($key = 0)

                                            @foreach($buyWith as $item)

                                                @if($item->count > 0)
                                                    @php($key++)

@include('catalog.snippets.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('catalog.snippets.tr_partial_analog')
                                                @endif
                                            @endforeach

                                           </tbody>
                                        </table>
                                    </div>

                                </div>

                            @endif 

                        </div>
                    </div>

                    @endif

                </div>
            </div>

        </section>

        <div class="w-popup _slider">
            <ul class="item-slider">
                @forelse($item_card->images as $image)
                    <li>
                        <a class="grouped_elements" rel="group1"
                           href=" {{asset($imageResize->resize($image->path_image))}}">
                            <img src=" {{asset($imageResize->resize($image->path_image))}}" alt=""

                                 class="imageResize">
                        </a>
                    </li>
                @empty
                    <li>
                        <a class="grouped_elements" rel="group1"
                           href=" {{asset($imageResize->resize(('500100')))}}">
                            <img src=" {{asset($imageResize->resize(('500100')))}}" alt="">
                        </a>
                    </li>
            @endforelse

        </div>
        @include('general.popups')
    </div>

{{-- Переключатели для аналогов и уценки --}}
<script type="text/javascript" src="{{ asset('assets/js/togglers.js') }}"></script>

    @include('general.scripts')
    </body>
@endsection