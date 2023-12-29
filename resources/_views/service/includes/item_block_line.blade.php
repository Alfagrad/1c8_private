@php
    // является категория товара расходным материалом
    if(in_array($item->{'1c_category_id'}, $supplies_id)) {
        $supplies = 1;
    } else {
        $supplies = 0;
    }
@endphp

@if($GLOBALS['i'])
    {{-- Если выводится аналог --}}
    <tr style="display: none;" class="js-item-{{ $id_item }} spare-analog_line">

@elseif(isset($buyForget))
    {{-- Это строка для вывода "Не забудь купить" в карточке товара --}}
    @php
        if(!isset($key)) {
            $key = 0;
        }
    @endphp

    <tr class=" @if($key>4)category-id-forget _disable after @endif">

@elseif(isset($actions))
    {{-- Это строка для вывода "Акционные товары" в корзине --}}
    @php
        if(!isset($key)) {
            $key = 0;
        }
    @endphp

    <tr class=" @if($key>4)category-id-action _disable after @endif">

@elseif(isset($itemsFromOrder))
    {{-- Это строка для вывода "Ранее купленные" в корзине --}}
    @php
        if(!isset($key)) {
            $key = 0;
        }
    @endphp

    <tr class=" @if($key>4)category-id-beforeBuy _disable after @endif">

@elseif(isset($buyWithCategory) || isset($buyWith))
    {{-- Это строка для вывода "С этим товаром покупают" в карточке товара --}}
    @php
        if(!isset($key)) {
            $key = 0;
        }
    @endphp

    <tr class=" @if($key>4)category-id-{{ $buyCat->id }} _disable after @endif">

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
    >

@else

    <tr data-action="0" data-cheap="0" data-new="0">

@endif

        <td class="td-image">

            @if($item->images->count() )

                <div class="cat_img">
                    <a href="{{route('service-item-view', ['itemId' => $item->{'1c_id'}])}}"
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
                <a href="{{route('service-item-view', ['itemId' => $item->{'1c_id'}])}}" class="name ">{{$item->name}}</a>

                @if($item->guide_file)

                    <a href="{{ asset('storage/item-images/manuals/'.$item->guide_file) }}" title="Скачать руководство по эксплуатации" class="cat_ico" target="_blank">
                        <img src="{{asset('assets/img/doc_ico.png')}}">
                    </a>

                @endif

                @if($item->certificate_file AND ($item->certificate_exp == '0000-00-00' OR $item->certificate_exp >= date('Y-m-d')))

                    <a href="{{ asset('storage/item-images/certificates/'.$item->certificate_file) }}" title="Скачать сертификат" class="cat_ico" target="_blank">
                        <img src="{{asset('assets/img/pdf_ico.png')}}">
                    </a>

                @endif

                @if($item->getSchemeParent()->count())

                    <a href="{{route('service-item-view', ['itemId' => $item->{'1c_id'}])}}?spares=true"
                       class="cat_ico js-view-spares" type="button" title="Запчасти">
                        <img src="{{asset('assets/img/shesternia_ico.png')}}">
                    </a>

                @endif

                {{-- Ссылка для показа аналогов --}}
                {{-- Для самих аналогов не выводим --}}
                @if(!$is_analog_line)
                @include('catalog.snippets.analog_link')
                @endif

            </div>
        </td>

        <td class="td-pcs">

        @if($item->is_component == 1 || $item->is_component == 2 || $supplies == 1)

            <div class="pcs-controll _minus js-item-remove-from-cart">
                -
            </div>

            <input 
                type="number"
                name="item_count"
                data-item_id="{{$item->id}}"
                data-1cid="{{($item->{'1c_id'})}}"
                id="item-{{$item->id}}"
                class="table-price-input"
                value="@if(isset($idToCart[$item->id])){{ $idToCart[$item->id] }}@else{{ '0' }}@endif"
                step="1" 
                onfocus="this.removeAttribute('readonly');" readonly autocomplete="off"
            >

            <div class="pcs-controll _plus js-item-add-to-cart">
                +
            </div>

        @endif

        </td>

{{--         @if($item->count > 0)

            @if($item->count > 10)

        <td class="td-valible">
            <div class="icon _yes _verylot _hidden-info">
                В наличии 
                @if(Auth::user()->role_id == '5' || Auth::user()->role_id == '1')
                <br><strong>{{ $item->count }} шт.</strong>
                @endif
                <div class="cursor-hover-info">Доступно более 10шт</div>
            </div>
        </td>

            @elseif($item->count >= 5 and $item->count <= 10 )

        <td class="td-valible">
            <div class="icon _yes _lot _hidden-info">
                В наличии
                @if(Auth::user()->role_id == '5' || Auth::user()->role_id == '1')
                <br><strong>{{ $item->count }} шт.</strong>
                @endif
                <div class="cursor-hover-info">на складе {{$item->count}}шт</div>
            </div>
        </td>

            @else

        <td class="td-valible">
            <div class="icon _yes  _hidden-info">
                В наличии
                @if(Auth::user()->role_id == '5' || Auth::user()->role_id == '1')
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
 --}}


    </tr>
