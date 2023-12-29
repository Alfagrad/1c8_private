<div class="wrapper w-product-claps" id="w-product-claps">
    <div class="wrapper w-claps">
        <a href="javascript: void(0);" class="clap _active" role="button">Не забудь купить</a>



        <a href="javascript: void(0);" class="clap" role="button">C этим товаром покупают</a>
    </div>

    <!-- Содержимое табов -->

    <div class="wrapper clap-content items-table catalog-table fade in _active"
         style="    padding: 0px; background-color:transparent;">
        <div class="wrapper">
            @if($buyForget->count())

                <div class="w-table">
                    <div class="table-head">
                        <table>
                            <thead>
                            <tr>
                                <td class="td-image"><div data-category="forget" class="toggler-buttonfor-cart"></div> </td>
                                <td class="td-article"></td>
                                <td class="td-name"></td>
                                <td class="td-sale-colored"></td>
                                <td class="td-more"></td>
                                <td class="td-sale"></td>
                                <td class="td-price _mrp _byn">МРЦ
                                    <div class="content-show-usd">USD</div>
                                    <div class="content-show-byn">BYN</div>
                                </td>
                                <td class="td-price _byn">Цена
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

                            @foreach($buyForget as $buy)

                                @if($buy->count > 0)
                                    @php($key++)
@include('catalog.partials.item_line', ['item' => $buy, 'key' => $key, 'categoryId' => 'forget',])
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>

        <div class="wrapper">
            @if($actions->count())

                <div class="w-table">
                    <div class="table-head">
                        <table>
                            <thead>
                            <tr>
                                <td class="td-image"><div data-category="action" class="toggler-buttonfor-cart"></div> </td>
                                <td class="td-article"></td>
                                <td class="td-name">Акционные товары</td>
                                <td class="td-sale-colored"></td>
                                <td class="td-more"></td>
                                <td class="td-sale"></td>
                                <td class="td-price _mrp _byn">МРЦ
                                    <div class="content-show-usd">USD</div>
                                    <div class="content-show-byn">BYN</div>
                                </td>
                                <td class="td-price _byn">Цена
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

                            @foreach($actions as $buy)

                                @if($buy->count > 0)
                                    @php($key++)
@include('catalog.partials.item_line', ['item' => $buy, 'key' => $key, 'categoryId' => 'action',])
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>

        <div class="wrapper">
            @if($itemsFromOrder->count())

                <div class="w-table">
                    <div class="table-head">
                        <table>
                            <thead>
                            <tr>
                                <td class="td-image"><div data-category="beforeBuy" class="toggler-buttonfor-cart"></div> </td>
                                <td class="td-article"></td>
                                <td class="td-name">Ранее купленные</td>
                                <td class="td-sale-colored"></td>
                                <td class="td-more"></td>
                                <td class="td-sale"></td>
                                <td class="td-price _mrp _byn">МРЦ
                                    <div class="content-show-usd">USD</div>
                                    <div class="content-show-byn">BYN</div>
                                </td>
                                <td class="td-price _byn">Цена
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

                            @foreach($itemsFromOrder as $buy)

                                @php($key++)
@include('catalog.partials.item_line', ['item' => $buy, 'key' => $key, 'categoryId' => 'beforeBuy'])
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>



    </div>
    <div class="wrapper clap-content fade   "
         style="    padding: 0px; background-color:transparent;">
        <div class="wrapper">
            <div class="wrapper items-table catalog-table ">

                @foreach($buyWithCategory as $buyCat)

                    <div class="w-table">
                        <div class="table-head">
                            <table>
                                <thead>
                                <tr>
                                    <td class="td-image"></td>
                                    <td class="td-article"></td>
                                    <td class="td-name">{{$buyCat->name}}</td>
                                    <td class="td-sale-colored"></td>
                                    <td class="td-more"></td>
                                    <td class="td-sale"></td>
                                    <td class="td-price _mrp _byn">МРЦ
                                        <div class="content-show-usd">USD</div>
                                        <div class="content-show-byn">BYN</div>
                                    </td>
                                    <td class="td-price _byn">Цена
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
                                @foreach($buyCat->items as $buy)
                                    @if($buy->count > 0)
@include('catalog.partials.item_line', ['item' => $buy])
                                    @endif
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                @endforeach
            </div>

        </div>



    </div>



</div>