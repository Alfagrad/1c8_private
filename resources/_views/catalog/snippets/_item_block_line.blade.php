@php
    // если есть markup, учитываем при отображении цен
    if($data_markup) {

        if($data_markup) $markup = $data_markup / 100 + 1;
            else $markup = 1;
        // определяем максимально возможный для USD (для уцененных товаров не учитываем)
        if($item->usd_price != 0 && $item->{'1c_category_id'} != '3149') $maxMarkupUSD = $item->price_mr_usd/$item->usd_price;
            else $maxMarkupUSD = 1;
        // если получается больше максимального, ставим максимально возможный
        if($markup > $maxMarkupUSD) $markupUSD = $maxMarkupUSD;
            else $markupUSD = $markup;

        // определяем максимально возможный для BYN (для уцененных товаров не учитываем)
        if($item->bel_price != 0 && $item->{'1c_category_id'} != '3149') $maxMarkupBYN = $item->price_mr_bel/$item->bel_price;
            else $maxMarkupBYN = 1;
        // если получается больше максимального, ставим максимально возможный
        if($markup > $maxMarkupBYN) $markupBYN = $maxMarkupBYN;
            else $markupBYN = $markup;
    } else {
        $markupUSD = $markupBYN = 1;
        $maxMarkupUSD = $maxMarkupBYN = '';
    }
    // ***********************************************
// dd($markupBYN);

    // формируем строку скидок для акционных товаров
    if($item->discounted_rub) {
        // делим скидки
        $discounts = explode(';', $item->discounted_rub);
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
        foreach ($listDiscount as $price) {
            $actionString .= ' от ' . $price['count'] . ' шт ' . number_format( ceil((float)$price['price'] * $markupBYN *100) / 100, 2 ) . ' руб, ';
        }
        if ($actionString) {
            $actionString = substr($actionString, 0, -2);
        }

        if($item->is_action) {
            // расчитываем % скидки для акции
            $end = end($listDiscount);
            if($item->bel_price != 0) $maxPrs = round((1 - ((float)$end['price'] * $markupBYN) / ($item->bel_price * $markupBYN)) * 100);
                else $maxPrs = 0;
            $discountString = "Акция - ".$maxPrs."%";
        }
    }
    // ***********************************************

    // назначаем класс для фильтра "Показать"
    if($item->count > 0) {
        $availability_class = "js-avalible-item";
    } elseif ($item->count_type == 2) {
        $availability_class = "js-reserve";
    } elseif ($item->count_type == 3) {
        $availability_class = "js-soon";
    } else {
        $availability_class = "js-out-of-stock";
    }

    // назначаем класс для фильтра "Новинка"
    if ($item->is_new_item) {
        $js_new_item = "js_new_item";
    } else {
        $js_new_item = "";
    }
    // назначаем класс для фильтра "Акции"
    if ($item->is_action) {
        $js_action = "js_action";
    } else {
        $js_action = "";
    }

@endphp

@if($GLOBALS['i'])
    {{-- Если выводится аналог --}}
    <tr style="display: none;" class="js-item-{{ $id_item }} spare-analog_line">

@elseif(isset($buyForget))
    {{-- Это строка для вывода "Не забудь купить" в карточке товара --}}
    @if(!isset($key)) @php($key = 0) @endif

    <tr class=" @if($key>4) category-id-forget _disable after @endif">


@elseif(isset($actions))
    {{-- Это строка для вывода "Акционные товары" в корзине --}}
    @if(!isset($key)) @php($key = 0) @endif

    <tr class=" @if($key>4) category-id-action _disable after @endif">

@elseif(isset($itemsFromOrder))
    {{-- Это строка для вывода "Ранее купленные" в корзине --}}
    @if(!isset($key)) @php($key = 0) @endif

    <tr class=" @if($key>4)category-id-beforeBuy _disable after @endif">

{{-- @elseif(isset($buyWithCategory) && $buyWithCategory->count()) --}}
    {{-- Это строка для вывода "С этим товаром покупают" в карточке товара --}}

    {{-- <tr class=" @if($key>4)category-id-{{ $buyCat->id }} _disable after @endif"> --}}

@elseif(isset($buyWith) && $buyWith->count())
    {{-- Это строка для вывода "С этим товаром покупают" в карточке товара --}}
    @if(!isset($key)) @php($key = 0) @endif

    <tr class=" @if($key>4)category-id-bywith _disable after @endif">

@elseif($item->count > 0)

    <tr 
    @if($item->is_action != '0' OR $item->discounted != '0') data-action="1"
        @else data-action="0"
    @endif
    @if(isset($item->cheap_good->name) != '' or $item->mini_text != '' or $item->viewPriceList()) data-cheap="1"
        @else data-cheap="0"
    @endif
    @if($item->is_new_item == '1') data-new="1"
        @else data-new="0"
    @endif

    class="js-item-line {{ $availability_class }} {{ $js_new_item }} {{ $js_action }} filtered">

@else

    <tr data-action="0" data-cheap="0" data-new="0" class="js-item-line {{ $availability_class }} filtered">

@endif

        <td class="td-image">

            @if($item->images->count() )

                <div class="cat_img">
                    <a href="{{route('itemCard', ['itemId' => $item->{'1c_id'}])}}"
                       class="hovered-product"
                       data-big="{{asset($imageResize->resize($item->images->first()->path_image, 240))}}"
                       data-hasqtip="0"
                    >
                        <img src="{{asset($imageResize->resize($item->images->first()->path_image, 66))}}">

                        @if($item->is_new_item == 1)
                        <div class="hovered-product_new-item-sign">NEW</div>
                        @endif
                    </a>
                </div>

            @else

                <img src="{{asset('upload/no-thumb.png')}}" height="66px">

            @endif

        </td>

        <td class="td-article">
            <div class="mobile-helper">Код:</div>
            {{$item->code}}
        </td>

        {{-- Если выводятся запчасти для товара (в карточке) --}}
        @if(isset($item_spares))

        <td class="td-article">
            №{{$scheme_parts->scheme_no}}
        </td>

        <td class="td-article">
            №{{$scheme_parts->number_in_schema}}
        </td>

        @endif

        <td class="td-name" style="vertical-align: middle" data-name="{{strtolower($item->name)}}" colspan="2">
            <div class="links-item">
                <a href="{{route('itemCard', ['itemId' => $item->{'1c_id'}])}}" class="name ">{{$item->name}}</a>

                @if($item->discounted_rub)

                    <span style="color:red">{{ $actionString }}</span>

                @endif

                @if($item->discounted_rub && $item->is_action)

                    <div class="sticker _sale _hidden-info"

                         @if($item->mini_text != '')

                         rel="tipsy"
                         original-title="{{$item->mini_text}}"

                         @endif>

                         {{ $discountString }}
                    </div>

                @endif

{{--                 @if(isset($item->gift->name))

                    <div class="sticker _gift _hidden-info">Подарок
                        <div class="cursor-hover-info">{{$item->gift->name}}</div>
                    </div>

                @endif
 --}}
                @if(trim($item->youtube))

                    @php
                        $youtube_array = array_diff(explode(';', trim($item->youtube)), array('', NULL, false));
                        $youtube_code = '';
                    @endphp

                    @foreach($youtube_array as $youtube_link)

                        @php
                            if(count(explode('=', $youtube_link)) > 1) 
                                $youtube_code .= explode('=', $youtube_link)[1].";";
                            else
                                $youtube_code .= explode('.be/', $youtube_link)[1].";";
                        @endphp

                    @endforeach

                    <div title="Смотреть видео о товаре на Youtube" class="cat_ico js_video_link" video="{{ $youtube_code }}">
                        <img src="{{ asset('assets/img/youtube_ico.png') }}">
                    </div>

                @endif

                @if($item->guide_file)

                    <a href="{{ asset('storage/item-images/manuals/'.$item->guide_file) }}" title="Скачать руководство по эксплуатации" class="cat_ico" target="_blank">
                        <img src="{{asset('assets/img/doc_ico.png')}}">
                    </a>

                @endif

{{--                 @if($item->certificate_file AND ($item->certificate_exp == '0000-00-00' OR $item->certificate_exp >= date('Y-m-d')))

                    <a href="{{ asset('storage/item-images/certificates/'.$item->certificate_file) }}" title="Скачать сертификат" class="cat_ico" target="_blank">
                        <img src="{{asset('assets/img/pdf_ico.png')}}">
                    </a>

                @endif
 --}}
                <a href="{{ asset('pricetag/form/'.$item->{'1c_id'}) }}" title="Сформировать ценник" target="_blank" class="cat_ico">
                    <img src="{{asset('assets/img/price_tag_ico.png')}}">
                </a>

                @if($item->getSchemeParent()->count())

                    <a href="{{route('itemCard', ['itemId' => $item->{'1c_id'}])}}?spares=true"
                       class="cat_ico js-view-spares" type="button" title="Запчасти">
                        <img src="{{asset('assets/img/shesternia_ico.png')}}">
                    </a>

                @endif
{{-- @php dd($item_analogs); @endphp --}}
                {{-- Ссылка для показа аналогов --}}
                {{-- Для самих аналогов не выводим --}}
                @if(!$is_analog_line)
                @include('catalog.snippets.analog_link')
                @endif

            </div>

            {{-- Выводим уцененный товар --}}
            @if($item->cheap_goods)

                @php
                    // выбираем коды уцененных товаров
                    $cheaps = explode(',', $item->cheap_goods);
                    // делаем метку, если аналог прописан, но не существует
                    $x = 0;
                    foreach ($cheaps as $key => $value) {
                        // выбираем id товара
                        $ch = $chipItems->where('1c_id', $value)->first();
                        // если не пусто, увеличиваем $x
                        if (!$ch) {
                            unset($cheaps[$key]); // удаляем, если товар не существует
                        } else $x++;
                    }
                @endphp

                {{-- Если товары существуют, выдаем --}}
                @if($x)

            <div class="discount-items">
                <div class="discount-items_header">Уцененные товары (торг):

                @php
                    $z = 1; // вводим счетчик товаров
                @endphp

                @foreach($cheaps as $chipId)

                    @php
                        // выбираем данные товара
                        $cheap = $chipItems->where('1c_id', $chipId)->first();
                        // добавляем аттрибут стиля и класс для ссылки на товар
                        if($z <= 2) {
                            $disp = "block";
                            $cls = "";
                        } else {
                            $disp = "none";
                            $cls = "js-chip-".$item->{'1c_id'};
                        }
                    @endphp

                    <a href="{{route('itemCard', ['itemId' => $cheap->{'1c_id'}])}}" target="_blank" style="display: {{ $disp }};" class="{{ $cls }}">
                        {{ $cheap->name }} -
                        <span style="color: red">
                            {{ number_format( $cheap->priceMinDiscountByn,  2, '.',' ') }}
                            руб.
                        </span>
                    </a>

                    @php
                        $z++;
                    @endphp

                @endforeach

                @if(count($cheaps) > 2)

                <div class="discount-items_toggler js-down js-chip-down-{{ $item->{'1c_id'} }}" data-parent_id="{{ $item->{'1c_id'} }}" style="display: block;">
                    Показать еще {{ count($cheaps) - 2 }}
                    <img src="{{ asset('assets/img/toggler_arrow.png') }}">
                </div>
                <div class="discount-items_toggler js-up js-chip-up-{{ $item->{'1c_id'} }}" data-parent_id="{{ $item->{'1c_id'} }}" style="display: none;">
                    Скрыть
                    <img src="{{ asset('assets/img/toggler_arrow.png') }}" style="transform: rotate(180deg);">
                </div>

                @endif

            </div>

                @endif
            @endif

        </td>

        <!-- Мини-текст для акционных товаров -->
        @if($item->mini_text != '')

        <td class="td-sale">
            <div class="roll _sale _hidden-info">
                %
                <div class="cursor-hover-info">
                    {{$item->mini_text}}
                </div>
            </div>
        </td>

        @else

        <td class="td-sale"></td>

        @endif

        <td class="td-price _{{$currency}}">
            @if($item->discounted != 0)

            <div class="content-show-usd @if($item->discounted != 0) _red @endif">
                {{ number_format(ceil($item->PriceMinDiscountUsd * $markupUSD * 100) / 100, 2) }}
                <div class="content-show-old-price @if($item->getMinCount() != 1) without-underline @endif">
                    {{ number_format(ceil($item->usd_price * $markupUSD * 100) / 100, 2) }}
                </div>
            </div>

            <div class="content-show-byn @if($item->discounted != 0) _red @endif">
                {{ number_format(ceil($item->priceMinDiscountByn * $markupBYN * 100) / 100,  2, '.',' ') }}
                <div class="content-show-old-price @if($item->getMinCount() != 1) without-underline @endif">
                    {{ number_format(ceil($item->bel_price * $markupBYN * 100) / 100, 2, '.', ' ') }}
                </div>
            </div>

            @else

            <div class="content-show-usd">{{ number_format(ceil($item->usd_price * $markupUSD * 100) / 100, 2) }}</div>

            <div class="content-show-byn">{{ number_format(ceil($item->bel_price * $markupBYN * 100) / 100, 2, '.', ' ') }}</div>

            @endif

        </td>

        <td class="td-price _{{$currency}} ">
            <div class="content-show-byn">

                @if($item->{'1c_category_id'} != '3149')  {{-- Если учененный товар, не выводим! --}}

                    @if($item->price_mr_bel != 0 && $item->bel_price !=0 )

                        @if($item->discounted != 0 ) 

                            @if($item->priceMinDiscountByn != 0)

                            {{round(((($item->price_mr_bel/($item->priceMinDiscountByn * $markupBYN))-1)*100))}} %

                            @endif

                        @else

                            @if($item->bel_price != 0)

                                @if($markupBYN == $maxMarkupBYN)
                                    0
                                @else
                                    {{round(((($item->price_mr_bel/($item->bel_price * $markupBYN))-1)*100))}}
                                @endif

                                %

                            @endif

                        @endif

                    @endif

                @endif
            </div>

            <div class="content-show-usd">

                @if($item->{'1c_category_id'} != '3149')  {{-- Если учененный товар, не выводим! --}}

                    @if($item->price_mr_usd != 0 && $item->usd_price !== 0 )

                        @if($item->discounted != 0 )

                            @if($item->priceMinDiscountUsd != 0)

                            {{round(((($item->price_mr_usd/($item->priceMinDiscountUsd * $markupUSD))-1)*100))}} %

                            @endif

                        @else

                            @if($item->usd_price != 0)

                                @if($markupUSD == $maxMarkupUSD)
                                    0
                                @else
                                    {{round(((($item->price_mr_usd/($item->usd_price * $markupUSD))-1)*100))}}
                                @endif

                                %

                            @endif

                        @endif

                    @endif

                @endif

            </div>
        </td>

        <td class="td-price _mrp _{{$currency}}">

            @if($item->{'1c_category_id'} != '3149')  {{-- Если учененный товар, не выводим! --}}

            <div class="content-show-usd">{{ $item->price_mr_usd }}</div>
            <div class="content-show-byn">{{ $item->price_mr_bel }}</div>

            @endif

        </td>

        <td class="td-take-discount">
            <a href="" class="roll _discount _hidden-info js-b-discount"
               data-item_id="{{$item->id}}" data-item_name="{{$item->name}}">!
                <div class="cursor-hover-info">Если дорого, нажми!</div>
            </a>
        </td>



        <td class="td-pcs">

        @if($item->count > 0 && $item->in_archive != 1 )

            <div class="pcs-controll _minus js-item-remove-from-cart">
                -
            </div>

            <input 
                type="number"
                name="item_count"
                data-item_id="{{$item->id}}"
                data-1cid="{{ $item->{'1c_id'} }}"
                id="item-{{$item->id}}"
                class="table-price-input"
                value="@if(isset($idToCart[$item->{'1c_id'}])){{ $idToCart[$item->{'1c_id'}] }}@else{{ '0' }}@endif"
                step="{{ $item->packaging }}" 
                {{-- step_index = "0" --}}
                onfocus="this.removeAttribute('readonly');" readonly autocomplete="off"
            >

            <div class="pcs-controll _plus js-item-add-to-cart">
                +
            </div>

            @if($item->packaging > 1)

            <div class="_hidden-info js-item-add-to-cart-package" data-block_id="{{$item->packaging}}" onclick="update({{$item->id}}, {{$item->packaging}})">
                В упаковке {{$item->packaging}} шт.
                <div class="cursor-hover-info-bottom ">Жми, чтобы добавить  {{$item->packaging}} шт.</div>
            </div>

            @endif

        @endif

        </td>

        @if($item->count > 0 && $item->in_archive == 1)

        <td class="td-valible">
            <div class="icon _no _reserved _hidden-info">Уточните наличие
                @if(Auth::user()->role_id != '2')
                <br><strong>{{ $item->count }} шт.</strong>
                @endif
                <div class="cursor-hover-info">Звоните</div>
            </div>
        </td>

        @elseif($item->count > 0)

            @if($item->count > 10)

        <td class="td-valible">
            <div class="icon _yes _verylot _hidden-info">
                В наличии 
                @if(Auth::user()->role_id != '2')
                <br><strong>{{ $item->count }} шт.</strong>
                @endif
                <div class="cursor-hover-info">Доступно более 10шт</div>
            </div>
        </td>

            @elseif($item->count >= 5 and $item->count <= 10 )

        <td class="td-valible">
            <div class="icon _yes _lot _hidden-info">
                В наличии
                @if(Auth::user()->role_id != '2')
                <br><strong>{{ $item->count }} шт.</strong>
                @endif
                <div class="cursor-hover-info">на складе {{$item->count}}шт</div>
            </div>
        </td>

            @else

        <td class="td-valible">
            <div class="icon _yes  _hidden-info">
                В наличии
                @if(Auth::user()->role_id != '2')
                <br><strong>{{ $item->count }} шт.</strong>
                @endif
                <div class="cursor-hover-info">на складе {{$item->count}} шт</div>
            </div>
        </td>

            @endif

        @elseif($item->count_type == 2)

        <td class="td-valible">
            <div class="icon _no _reserved _hidden-info">Резерв
                <div class="cursor-hover-info">Звоните</div>
            </div>
        </td>

        @elseif($item->count_type == 3)

        <td class="td-valible">
            <div class="icon _no _hidden-info">{{$item->count_text}}
                <div class="cursor-hover-info">Поступит {{$item->count_text}}</div>
            </div>
        </td>

        @elseif($item->count_type == 4)

        <td class="td-valible">
            <div class="icon _no _reserved _hidden-info">Нет
                <div class="cursor-hover-info">Нет на складе</div>
            </div>
        </td>

        @endif

    </tr>
