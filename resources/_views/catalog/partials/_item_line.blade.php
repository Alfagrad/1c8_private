
@php
if(!isset($categoryId)) {
    $categoryId = 0;
}
if(!isset($key)) {
    $key = 0;
}
@endphp
<tr class=" @if($key>4)category-id-{{$categoryId}} _disable after @endif">
    <td class="td-image">
        @if($item->images->count())
            <a href="{{route('itemVies', ['itemId' => $item->{'1c_id'}])}}"
               class=" hovered-product"
               data-big="{{asset($imageResize->resize($item->images->first()->path_image, 240,240))}}"
               data-hasqtip="0">
                <img src="{{asset($imageResize->resize($item->images->first()->path_image, 66))}}">

                @if($item->is_new_item == 1)
                <div class="hovered-product_new-item-sign">NEW</div>
                @endif
            </a>
        @else
            <img src="{{asset('upload/no-thumb.png')}}" height="66px">
        @endif
    </td>
    <td class="td-article"><div class="mobile-helper">Код: </div>{{$item->code}}</td>
    <td class="td-name">
        <a href="{{route('itemVies', ['itemId' => $item->{'1c_id'}])}}" class=" name  js-open-window">{{$item->name}}</a>
        @if($item->viewPriceList())
            <span style="color:red">{{$item->viewPriceList()}}</span>
        @endif
    </td>
    <td class="td-sale-colored">
        @if($item->is_action != 0)
            <div class="sticker _sale _hidden-info">Акция -{{$item->discountValue}}%</div>
        @endif

        @if(isset($item->gift->name))
            <div class="sticker _gift _hidden-info">Подарок<div class="cursor-hover-info">{{$item->gift->name}}</div></div>
        @endif
    </td>

    <td class="td-more">
        @if($item->more_about != 'Подробнее о товаре:')
            <a href="{{route('itemVies', ['itemId' => $item->{'1c_id'}])}}" target="_blank" class="roll _info _hidden-info js-open-window">i<div class="cursor-hover-info">{{$item->more_about or 'Подробнее о товаре'}}</div></a></td>
        @endif
    @if($item->cheap_goods != '' or $item->mini_text != '')
        <td class="td-sale"><div class="roll _sale _hidden-info">%<div class="cursor-hover-info">
                    @if($item->mini_text != '')
                        {{$item->mini_text}}<br>
                    @endif
                    @if($item->viewPriceList())
                            <span style="color:red">{{$item->viewPriceList()}}</span> <br>
                    @endif
                </div></div>
        </td>
    @else
        <td class="td-sale"></td>
    @endif

    <td class="td-price _mrp _byn">
        <div class="content-show-usd">{{$item->price_mr_usd}}</div>
        <div class="content-show-byn">{{$item->price_mr_bel}}</div>
    </td>
    <td class="td-price _byn">
        <div class="content-show-usd @if($item->discounted != 0) _red @endif">{{$item->usd_price}}
            @if($item->discounted != 0)
                <div class="content-show-old-price @if($item->getMinCount() != 1) without-underline @endif">
{{--                        {{ number_format(($item->usd_price / ( (100 - $item->discounted) / 100)), 2, '.',' ')  }}--}}
                </div>
            @endif
        </div>
        <div class="content-show-byn @if($item->discounted != 0) _red @endif">{{$item->bel_price}}
            @if($item->discounted != 0)
                <div class="content-show-old-price @if($item->getMinCount() != 1) without-underline @endif">  {{-- {{ number_format(($item->bel_price / ( (100 - $item->discounted) / 100)), 2, '.',' ')  }}--}}</div>
            @endif
        </div>
    </td>

    <td class="td-take-discount">
        <a href="" class="roll _discount _hidden-info js-b-discount" data-item_id="{{$item->id}}" data-item_name="{{$item->name}}">!<div class="cursor-hover-info">Если дорого, нажми!</div></a>
    </td>

    <td class="td-pcs">
@include('catalog.snippets.td_pcs')
    </td>



    @if($item->count > 0)
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
                    <div class="cursor-hover-info">на складе {{$item->count}} шт</div>
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
        <td class="td-valible"><div class="icon _no _reserved _hidden-info">Резерв<div class="cursor-hover-info">Звоните</div></div></td>
    @elseif($item->count_type == 3)
        <td class="td-valible"><div class="icon _no _hidden-info">{{$item->count_text}} <div class="cursor-hover-info">Поступит {{$item->count_text}}</div></div></td>
    @elseif($item->count_type == 4)
        <td class="td-valible"><div class="icon _no _reserved _hidden-info">Нет<div class="cursor-hover-info">Нет на складе</div></div></td>
    @endif

</tr>