@extends('layouts.service')

@section('content')

@php 
    global $i;
    $GLOBALS['i'] = 0;
    $is_analog_line = 0; // метка для вывода аналогов

    // является ли категория товара расходным материалом
    if(in_array($item_card->{'1c_category_id'}, $supplies_id)) {
        $supplies = 1;
    } else {
        $supplies = 0;
    }
@endphp

    <body onload="
    @if($item_card->getSchemeParent()->count())
        spares_link({{ $item_card->id }}, '{{ $token }}', '{{ csrf_token() }}')
    @endif
    ">

    <div class="b-wrapper p-index">

        @include('service.includes.header')
        @include('service.includes.nav')

        <section class="s-product">
            <div class="container">
                <div class="wrapper w-product-main-info">

                    <div class="wrapper w-name">
                        <h1>{{$item_card->name}} Артикул: {{$item_card->vendor_code}}</h1>
                    </div>

                    <div class="w-product-content">
                        <div class="w-product-slider" style="">
                            <div class="top">

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

                                @if($item_card->certificate_file AND ($item_card->certificate_exp == '0000-00-00' OR $item_card->certificate_exp >= date('Y-m-d')))
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

                                @endif

                            </div>
                        </div>


                        <div class="w-product-info">
                            <div class="wrapper">

                                <div class="w-graybgr">
                                    <div class="wrapper flex-line">
                                        <div class="product-code">
                                            Код: {{$item_card->code}}
                                        </div>

{{--                                         @if($item_card->count > 0)

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
 --}}
                                    </div>

                                    @if($item_card->is_component == 1 || $item_card->is_component == 2 || $supplies == 1)

                                    <div class="wrapper w-pcs">

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
                                            step="1"
                                            {{-- step_index = "0" --}}
                                            onfocus="this.removeAttribute('readonly');" readonly autocomplete="off"
                                        >

                                        <div class="pcs-controll _plus js-item-add-to-cart">+</div> шт.

                                    </div>

                                    @endif

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
                                    <td class="td-more"
                                        style="vertical-align:top;  background-color: #F5F5F5; padding:  0 20px 0 20px; white-space:pre-line">
                                        <p
                                        >{!!  $item_card->content!!}</p></td>
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
                                                    Срок годности: {!!  $item_card->shelf_life !!}
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
                                            <div>
                                                @if ($item_card->depth &&  $item_card->width && $item_card->height)
                                                    Габариты:


                                                    {!!  $item_card->depth !!}X {!!  $item_card->width !!}X {!!  $item_card->height !!}
                                                    мм.;

                                                @endif
                                                @if($item_card->weight ) Вес с упаковкой: {!!  $item_card->weight !!} кг. @endif
                                            </div>
                                        </div>
                                    </td>
                                </tbody>
                            </table>
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

@include('service.includes.item_block_line')

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
        {{-- @include('general.popups') --}}
    </div>

{{-- Переключатели для аналогов и уценки --}}
<script type="text/javascript" src="{{ asset('assets/js/togglers.js') }}"></script>

    </body>
@endsection