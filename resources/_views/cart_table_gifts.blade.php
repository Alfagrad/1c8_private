<div class="w-main-table">
    <div class="left">
    </div>
    <div class="right _fullwidth">
        <div class="wrapper white-bg-wrapper page-inset-frame">
            <div class="wrapper items-table cart-table">
                <div class="w-table">
                    <div class="table-head">
                        <table>
                            <thead>
                            <tr>
                                <td class="td-article">Код</td>
                                <td class="td-name">Наименование</td>
                                <td class="td-more"></td>
                                <td class="td-sale"></td>
                                <td class="td-price">Цена</td>
                                <td class="td-price">Цена расчетная <br> <span>@if($personal_discount) {{$personal_discount}}% @else 0% @endif</span></td>
                                <td class="td-pcs"></td>
                                <td class="td-weight">Вес</td>
                                <td class="td-price">Итого</td>
                                <td class="td-delete-item"></td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-body">
                        <table>
                            <tbody>
                            @foreach($cart as $c)
                                <?php $item = $c->item?>
                                <?php
                                if($c->item->gift) {
                                    $gifts->push($c->item->gift);
                                }
                                ?>
                                <tr class="js-pos-item">
                                    <td class="td-article">{{$item->code}}</td>
                                    <td class="td-name">
                                        @if( $item->images->count() )
                                            <a class="catalog-photo-img hovered-product" data-big="{{asset($imageResize->resize($item->images->first()->path_image, 240,240))}}" data-hasqtip="0"><img src="{{asset('assets/img/photo-camera.svg')}}" alt=""></a>
                                        @endif
                                        <a href="{{route('itemVies', ['itemId' => $item->id])}}" class="name " >{{$item->name}}</a>

                                            @if($item->is_action != 0)
                                                <div class="sticker _sale _hidden-info" @if($item->mini_text != '') rel="tipsy" original-title="{{$item->mini_text}}" @endif>{{$item->discountValueText}}</div>
                                            @endif

                                            @if(isset($item->gift->name))
                                                <div class="sticker _gift _hidden-info">Подарок<div class="cursor-hover-info">{{$item->gift->name}}</div></div>
                                            @endif

                                            @if($item->getSchemeParent()->count())
                                                <a href="{{route('itemVies', ['itemId' => $item->id])}}?spares=true" class="spares-modal-btn js-view-spares" type="button"><svg role="img" aria-hidden="true" width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><path d="M15.603 6.512l-1.428-.172c-.146-.545-.36-1.059-.635-1.537l.888-1.128c.136-.173.12-.442-.037-.598l-1.471-1.472c-.155-.156-.424-.172-.598-.036l-1.13.889c-.476-.275-.99-.489-1.533-.634l-.171-1.428c-.026-.218-.228-.397-.448-.397h-2.081c-.22 0-.422.179-.447.397l-.172 1.428c-.544.145-1.059.36-1.535.634l-1.13-.888c-.173-.136-.442-.12-.597.036l-1.472 1.473c-.155.155-.171.424-.035.598l.887 1.129c-.275.476-.489.991-.634 1.536l-1.427.171c-.218.026-.397.227-.397.448v2.081c0 .22.179.42.397.447l1.428.172c.146.543.359 1.057.634 1.533l-.887 1.13c-.135.174-.12.442.036.598l1.469 1.473c.156.155.424.172.597.037l1.13-.889c.476.275.991.489 1.535.634l.172 1.427c.026.219.227.397.447.397h2.081c.221 0 .422-.178.447-.397l.172-1.427c.545-.146 1.059-.36 1.535-.634l1.13.888c.173.136.442.12.597-.036l1.471-1.473c.156-.155.172-.424.036-.598l-.888-1.129c.276-.476.489-.991.635-1.534l1.427-.172c.219-.026.397-.226.397-.447v-2.081c.001-.221-.177-.422-.397-.448zm-7.603 5.488c-2.209 0-4-1.791-4-4s1.791-4 4-4 4 1.791 4 4-1.79 4-4 4z"/></svg>Запчасти</a>
                                            @endif

                                    </td>


                                    <td class="td-more"><a href="{{route('itemVies', ['itemId' => $item->id])}}" class="roll _info _hidden-info">i<div class="cursor-hover-info">Подробнее о товаре</div></a></td>
                                    @if(isset($item->cheap_good->name) != '' or $item->mini_text != '')
                                        <td class="td-sale"><div class="roll _sale _hidden-info">%<div class="cursor-hover-info">

                                                    @if(isset($item->cheap_good->name))
                                                        <a href="{{route('itemVies', ['itemId' => $item->cheap_good->id])}}" class="name">{{$item->cheap_good->name}}</a>
                                                        <br>
                                                    @endif

                                                    @if($item->mini_text != '')
                                                        {{$item->mini_text}}
                                                    @endif

                                                </div></div></td>
                                    @else
                                        <td class="td-sale"></td>
                                    @endif


                                    <td class="td-price js-item-price" data-price="{{$item->price_bel}}" data-item_discount="{{$item->discounted or 0}}" @if($item->discounted)style="color:red;@endif">
                                        {{$item->price_bel}}
                                        @if($item->discounted != 0)
                                            <div class="content-show-old-price">   {{ number_format(($item->price_bel / ( (100 - $item->discounted) / 100)), 2, '.',' ')  }}</div>
                                        @endif
                                    </td>
                                    <td class="td-price overprice">
                                        0
                                    </td>
                                    <td class="td-pcs">
                                        @if( isset($item->count) ) {{$item->count}} @else 0 @endif
                                    </td>
                                    <td class="td-weight" data-weight="{{$item->weight}}">{{$item->weight}} кг</td>
                                    <td class="td-price">0</td>
                                    <td class="td-delete-item _hidden-info"></td>
                                </tr>
                            @endforeach
                            <tr class="tr-cart-bottom">
                                <td colspan="4" class="td-total-sale-info"></td>
                                <td colspan="3" class="td-total-weight-info"></td>
                                <td colspan="3" class="td-total-price"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>