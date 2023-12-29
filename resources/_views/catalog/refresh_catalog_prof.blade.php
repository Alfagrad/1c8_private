
@foreach($categories as $cat)


<div class="w-table _toggled">
    <div class="table-head">
        <table>
            <thead>
            <tr>
                <td class="td-article"><div class="toggler-button"></div></td>
                <td class="td-name">{{$cat->name}}</td>
                <td class="td-more"></td>
                <td class="td-sale"></td>
                <td class="td-price _mrp _{{$currency}}">МРЦ
                    <div class="content-show-usd">USD</div>
                    <div class="content-show-byn">BYN</div>
                </td>
                <td class="td-price _{{$currency}}">Цена
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
    <div class="table-body _toggled">
        <table>
            <tbody>
                @foreach($cat->items as $item)
                    @if($item->count > 0)
                        <tr @if($item->is_action != 0) data-action="1"  @else data-action="0" @endif @if($item->mini_text != '' or $item->viewPriceList()) data-cheap = "1" @else data-cheap = "0"  @endif>
                        <td class="td-article"><div class="mobile-helper">Код: </div>{{$item->code}}</td>
                        <td class="td-name" data-name="{{strtolower($item->name)}}">
                            <a href="{{route('itemVies', ['itemId' => $item->id])}}" class="name ">{{$item->name}}</a>
                            @if($item->viewPriceList())
                                <span style="color:red">{{$item->viewPriceList()}}</span>
                            @endif

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

                        <td class="td-more"><a href="{{route('itemVies', ['itemId' => $item->id])}}" class="roll _info _hidden-info ">i<div class="cursor-hover-info">{{$item->more_about or 'Подробнее о товаре'}}</div></a></td>

                        <!-- Уценный товар или и текст -->

                        @if(isset($item->cheap_good->name) != '' or $item->mini_text != '' or $item->viewPriceList())
                            <td class="td-sale">
                                <div class="roll _sale _hidden-info">%<div class="cursor-hover-info">
                                    @if($item->mini_text != '')
                                        {{$item->mini_text}} <br>
                                    @endif
                                    @if($item->viewPriceList())
                                        {{$item->viewPriceList()}} <br>
                                    @endif
                                    @if(isset($item->cheap_good->name))
                                        <a href="{{route('itemVies', ['itemId' => $item->cheap_good->id])}}" class="name">{{$item->cheap_good->name}}</a>
                                        <br>
                                    @endif
                                </div></div>
                            </td>
                        @else
                            <td class="td-sale"></td>
                        @endif


                        <td class="td-price _mrp _{{$currency}}">
                            <div class="content-show-usd">{{$item->price_mr_usd}}</div>
                            <div class="content-show-byn">{{$item->price_mr_bel}}</div>
                        </td>
                        <td class="td-price _{{$currency}}">
                            @if($item->discounted != 0)
                                <div class="content-show-usd @if($item->discounted != 0) _red @endif">{{ number_format( $item->PriceMinDiscountUsd,  2, '.',' ') }}
                                    <div class="content-show-old-price @if($item->getMinCount() != 1) without-underline @endif">{{$item->usd_price}}</div>
                                </div>
                            @else
                                <div class="content-show-usd @if($item->discounted != 0) _red @endif">{{$item->usd_price}}</div>
                            @endif

                            @if($item->discounted != 0)
                                <div class="content-show-byn @if($item->discounted != 0) _red @endif">{{ number_format( $item->priceMinDiscountByn,  2, '.',' ') }}
                                    <div class="content-show-old-price @if($item->getMinCount() != 1) without-underline @endif">{{$item->bel_price}}</div>
                                </div>
                            @else
                                <div class="content-show-byn @if($item->discounted != 0) _red @endif">{{$item->bel_price}}</div>
                            @endif
                        </td>
                        <td class="td-take-discount"><a href="" class="roll _discount _hidden-info js-get-discount" data-item_id="{{$item->id}}">!<div class="cursor-hover-info">Если дорого, нажми!</div></a></td>

@include('catalog.snippets.item_line')

                    </tr>
                    @endif
                @endforeach

                @foreach($cat->items as $item)
                    @if($item->count == 0)
                        <tr @if($item->is_action != 0) data-action="1"  @else data-action="0" @endif @if($item->mini_text != '' or $item->viewPriceList()) data-cheap = "1" @else data-cheap = "0"  @endif>
                            <td class="td-article"><div class="mobile-helper">Код: </div>{{$item->code}}</td>
                            <td class="td-name" data-name="{{strtolower($item->name)}}">
                                <a href="{{route('itemVies', ['itemId' => $item->id])}}" class="name ">{{$item->name}}</a>
                                @if($item->viewPriceList())
                                    <span style="color:red">{{$item->viewPriceList()}}</span>
                                @endif

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

                            <td class="td-more"><a href="{{route('itemVies', ['itemId' => $item->id])}}" class="roll _info _hidden-info ">i<div class="cursor-hover-info">{{$item->more_about or 'Подробнее о товаре'}}</div></a></td>

                            <!-- Уценный товар или и текст -->

                            @if(isset($item->cheap_good->name) != '' or $item->mini_text != '' or $item->viewPriceList())
                                <td class="td-sale">
                                    <div class="roll _sale _hidden-info">%<div class="cursor-hover-info">
                                            @if($item->mini_text != '')
                                                {{$item->mini_text}} <br>
                                            @endif
                                            @if($item->viewPriceList())
                                                {{$item->viewPriceList()}} <br>
                                            @endif
                                            @if(isset($item->cheap_good->name))
                                                <a href="{{route('itemVies', ['itemId' => $item->cheap_good->id])}}" class="name">{{$item->cheap_good->name}}</a>
                                                <br>
                                            @endif
                                        </div></div>
                                </td>
                            @else
                                <td class="td-sale"></td>
                            @endif


                            <td class="td-price _mrp _{{$currency}}">
                                <div class="content-show-usd">{{$item->price_mr_usd}}</div>
                                <div class="content-show-byn">{{$item->price_mr_bel}}</div>
                            </td>
                            <td class="td-price _{{$currency}}">
                                @if($item->discounted != 0)
                                    <div class="content-show-usd @if($item->discounted != 0) _red @endif">{{ number_format( $item->PriceMinDiscountUsd,  2, '.',' ') }}
                                        <div class="content-show-old-price @if($item->getMinCount() != 1) without-underline @endif">{{$item->usd_price}}</div>
                                    </div>
                                @else
                                    <div class="content-show-usd @if($item->discounted != 0) _red @endif">{{$item->usd_price}}</div>
                                @endif

                                @if($item->discounted != 0)
                                    <div class="content-show-byn @if($item->discounted != 0) _red @endif">{{ number_format( $item->priceMinDiscountByn,  2, '.',' ') }}
                                        <div class="content-show-old-price @if($item->getMinCount() != 1) without-underline @endif">{{$item->bel_price}}</div>
                                    </div>
                                @else
                                    <div class="content-show-byn @if($item->discounted != 0) _red @endif">{{$item->bel_price}}</div>
                                @endif
                            </td>
                            <td class="td-take-discount"><a href="" class="roll _discount _hidden-info js-get-discount" data-item_id="{{$item->id}}">!<div class="cursor-hover-info">Если дорого, нажми!</div></a></td>

@include('catalog.snippets.item_line')

                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
    @endforeach