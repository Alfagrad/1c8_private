@php
    $item = $c->item;

    // if ($c->item->gift) {
    //     $c->item->gift->gift_count = $c->count;
    //     $gifts->push($c->item->gift);
    // }

    // если есть markup, учитываем при отображении цен
    if($data_markup) {

        if($data_markup) $markup = $data_markup / 100 + 1;
            else $markup = 1;

        // определяем максимально возможный для BYN (для уцененных товаров не учитываем)
        if($item->bel_price != 0 && $item->{'1c_category_id'} != '3149') $maxMarkupBYN = $item->price_mr_bel/$item->bel_price;
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
            $actionString .= ' от ' . $price['count'] . ' шт ' . number_format(ceil((float)$price['price'] * $markupBYN * 100) / 100, 2) . ' руб, ';
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

    // рассчитываем суммируемый дискаунт
    if($personal_discount) $item_personal_discount = $personal_discount;
        else $item_personal_discount = 0;

    $item_total_discount = $item_personal_discount;

@endphp


<tr class="js-pos-item" @if($item->count <= 0) style='background-color: mistyrose;' @endif>
    <td class="td-more">
        <div class="input">
            <label class="for-checkbox-cart" title="выделить позицию для копирования/перемещения в другую корзину" style="left: 20px;">
                <input type="checkbox" name="item[]" value="{{ $item->{'1c_id'} }}"/>
            </label>
        </div>
    </td>
    <td class="td-image">
        @if($item->images->count())
            <a href="{{route('itemCard', ['itemId' => $item->{'1c_id'}])}}"
               class=" hovered-product"
               data-big="{{asset($imageResize->resize($item->images->first()->path_image, 240,240))}}"
               data-hasqtip="0">
                <img src="{{asset($imageResize->resize($item->images->first()->path_image, 66))}}">
            </a>
        @else
            <img src="{{asset('upload/no-thumb.png')}}" height="66px">
        @endif
    </td>
    <td class="td-article">{{$item->code}}</td>

    <td class="td-name">
        <a href="{{ route('itemCard', ['itemId' => $item->{'1c_id'}]) }}"
           class="name ">{{ $item->name }}</a>

        @if($item->viewPriceList())
            <span style="color:red">
                {{ $actionString }}
            </span>
        @endif

        @if($item->discounted_rub && $item->is_action)

            <div class="sticker _sale _hidden-info"

                 @if($item->mini_text != '')

                 rel="tipsy"
                 original-title="{{ $item->mini_text }}"

                 @endif>

                 {{ $discountString }}
            </div>

        @endif

{{--         @if(isset($item->gift->name))

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

{{--         @if($item->certificate_file AND ($item->certificate_exp == '0000-00-00' OR $item->certificate_exp >= date('Y-m-d')))

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

        @if($item->count <= 0) 
        <div style='color: red; font-weight: bold;'>! Уточните наличие этой позиции у Вашего менеджера !</div>
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

    <td class="td-price js-item-price"
        data-price="{{
            ($item->price_min_bel > $item->priceFromCountBYN($c->count) * $markupBYN)
            ? $item->price_min_bel
            : ceil($item->priceFromCountBYN($c->count) * $markupBYN * 100) /100
        }}"
        data-item_discount="{{$item->discounted or 0}}"
        data-item_is_action="{{$item->is_action}}"
        data-item_min_price="{{$item->price_min_bel}}"
        @if($item->discounted)style="color:red;@endif"
    >

        @if($item->discounted != 0)

        {{ number_format(ceil($item->priceMinDiscountByn * $markupBYN * 100) / 100, 2, '.', '') }}

            <div class="content-show-old-price @if($item->getMinCount() != 1) without-underline @endif">
                {{ number_format(ceil($item->bel_price * $markupBYN * 100) / 100, 2, '.', '') }}
            </div>

        @else

            {{ number_format(ceil($item->bel_price * $markupBYN * 100) / 100, 2, '.', '') }}

        @endif

    </td>

    {{-- Фиксируем связку код-цена --}}
    <input type="hidden" form="form-cart" class="js-price-rel" name="price[{{ $item->{'1c_id'} }}]" value="">

    <td class="td-price js-item-discount">
        {{-- {{ $item_total_discount }}% --}}

{{--         @if($personal_discount)

        {{ $personal_discount }}%

        @else

        0%

        @endif
 --}}
    </td>

    <td class="td-price overprice">

        @if($personal_discount)

            @php
                // $countPrice = $item->priceFromCountBYN($c->count) * $markupBYN - ($item->priceFromCountBYN($c->count) * $markupBYN * ($personal_discount / 100));
                // $countPrice = number_format($countPrice, 2);
                // $countMinPrice = $item->price_min_bel - ($item->price_min_bel * ($personal_discount / 100));
                // $countMinPrice = number_format($countMinPrice, 2);
            @endphp

            {{-- {{ ($countMinPrice > $countPrice) ? $countMinPrice : $countPrice }} --}}

        @else

{{--             {{ ($item->price_min_bel > $item->priceFromCountBYN($c->count) * $markupBYN)
                ? $item->price_min_bel
                : number_format($item->priceFromCountBYN($c->count) * $markupBYN, 2) }}
 --}}
        @endif
    </td>

    <td class="td-pcs">

    @if($item->count > 0)

        <div class="pcs-controll _minus js-item-remove-from-cart">-</div>

        <input
            type="number"
            name="item_count"
            data-item_id="{{ $item->id }}"
            data-1cid="{{ $item->{'1c_id'} }}"
            id="item-{{ $item->{'1c_id'} }}"
            class="table-price-input"
            value="@if(isset($c->count)){{ $c->count }}@else{{ '0' }}@endif"
            step="{{ $item->packaging }}" 
            {{-- step_index = "0" --}}
            onfocus="this.removeAttribute('readonly');" readonly autocomplete="off"
        >

        <div class="pcs-controll _plus js-item-add-to-cart">+</div>

        @if($item->packaging > 1  )

        <div class="_hidden-info js-item-add-to-cart-package"
             data-block_id="{{$item->packaging}}"
             onclick="update({{$item->id}}, {{$item->packaging}})">
            В упаковке {{$item->packaging}} шт.
            <div class="cursor-hover-info-bottom ">Жми, чтобы добавить  {{$item->packaging}} шт.</div>
        </div>

        @endif

    @endif

    </td>

    <td class="td-weight"
        data-weight="{{$item->weight}}">{{$item->weight}} кг
    </td>
    <td class="td-price js-item-total-price">
{{--         @if($personal_discount)
            {{($item->bel_price - ($item->bel_price * ($personal_discount / 100))) * $c->count}}
        @else
            {{$item->bel_price * $c->count}}
        @endif
 --}}
    </td>
    @if($item->count > 0)
        @if($item->count > 10)
            <td class="td-valible">
                <div class="icon _yes _verylot _hidden-info">
                    В наличии
                    @if(Auth::user()->role_id != '2')
                    <br><strong>{{ $item->count }} шт.</strong>
                    @endif
                    <div class="cursor-hover-info">Доступно
                        более 10шт
                    </div>
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
                    <div class="cursor-hover-info">на
                        складе {{$item->count}} шт
                    </div>
                </div>
            </td>
        @endif
    @elseif($item->count_type == 2)
        <td class="td-valible">
            <div class="icon _no _reserved _hidden-info">
                Резерв
                <div class="cursor-hover-info">Звоните</div>
            </div>
        </td>
    @elseif($item->count_type == 3)
        <td class="td-valible">
            <div class="icon _no _hidden-info">{{$item->count_text}}
                <div class="cursor-hover-info">
                    Поступит {{$item->count_text}}</div>
            </div>
        </td>
    @elseif($item->count_type == 4)
        <td class="td-valible">
            <div class="icon _no _reserved _hidden-info">Нет
                <div class="cursor-hover-info">Нет на
                    складе
                </div>
            </div>
        </td>
    @endif
    <td class="td-delete-item _hidden-info"><a href="#"
                                               class="js-delete-item-from-cart"
                                               data-1c_id="{{ $item->{'1c_id'} }}">&times;<div
                    class="cursor-hover-info">Удалить из
                корзины
            </div>
        </a></td>
</tr>
