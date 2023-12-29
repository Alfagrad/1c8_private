@php 
    global $i;
    $GLOBALS['i'] = 0;
    $is_analog_line = 0; // метка для вывода аналогов
@endphp

<div class="row">
    <div class="col-md-6">
        @foreach($breadcrumbs as $breadcrumb)
            <a href="{{asset('catalog/'.$breadcrumb['id'])}}" class="kroshka">{{$breadcrumb['name']}}</a>
            @if(!$loop->last)
                →

            @else
                <input type="hidden" class="categoryId" value="{{$breadcrumb['id']}}">
            @endif

        @endforeach
    </div>

</div>
<div>
    @foreach($categories as $cat)

        @if($cat->items->count() > 0)

            @php
                $data_in_stock = $cat->items->where('count', '>', 0)->where('in_archive', 0)->count();
                $data_soon = $cat->items->where('count_type', 3)->where('in_archive', 0)->count();
                $data_reserve = $cat->items->where('count_type', 2)->where('in_archive', 0)->count();
                $data_new_item = $cat->items->where('is_new_item', 1)->where('in_archive', 0)->count();
                $data_action_item = $cat->items->where('is_action', 1)->where('in_archive', 0)->count();
            @endphp

            <div
                class="w-table _toggled"
                data-in-stock-count="{{ $data_in_stock }}"
                data-soon-count="{{ $data_soon }}"
                data-reserve-count="{{ $data_reserve }}"
                data-new-item="{{ $data_new_item }}"
                data-action-item="{{ $data_action_item }}"
            >

                <div class="table-head">
                    <table>
                        <thead>
                        <tr>
                            <td class="td-image">
                                <div class="toggler-button"></div>
                            </td>

                            <td class="td-article">
                            </td>
                            <td class="td-name">{{$cat->name}}</td>
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

                <div class="table-body _toggled">
                    <table>
                        <tbody>

                        @foreach($cat->items->where('in_archive', 0) as $item)
                            @if($item->count > 0)

@include('catalog.snippets.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('catalog.snippets.tr_partial_analog')


                            @endif
                        @endforeach

                        @foreach($cat->items->where('in_archive', 0) as $item)
                            @if($item->count <= 0)

@include('catalog.snippets.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('catalog.snippets.tr_partial_analog')

                            @endif
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            @if($cat->items->where('in_archive', 1)->count() > 0)

                @php
                    $data_in_stock = $cat->items->where('count', '>', 0)->where('in_archive', 1)->count();
                    $data_soon = $cat->items->where('count_type', 3)->where('in_archive', 1)->count();
                    $data_reserve = $cat->items->where('count_type', 2)->where('in_archive', 1)->count();
                    $data_new_item = $cat->items->where('is_new_item', 1)->where('in_archive', 1)->count();
                    $data_action_item = $cat->items->where('is_action', 1)->where('in_archive', 1)->count();
                @endphp

            <div 
                class="w-table _toggled js-archive-items"
                style="display: none;" 
                data-in-stock-count="{{ $data_in_stock }}"
                data-soon-count="{{ $data_soon }}"
                data-reserve-count="{{ $data_reserve }}"
                data-new-item="{{ $data_new_item }}"
                data-action-item="{{ $data_action_item }}"
            >

                <div class="table-head">
                    <table>
                        <thead>
                            <tr>
                                <td class="td-image">
                                    <div class="toggler-button"></div>
                                </td>

                                <td class="td-article"></td>

                                <td class="td-name">{{$cat->name}} (архивные)</td>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="table-body _toggled">
                    <table>
                        <tbody>

                        @foreach($cat->items->where('in_archive', 1) as $item)
                            @if($item->count > 0)

@include('catalog.snippets.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('catalog.snippets.tr_partial_analog')


                            @endif
                        @endforeach

                        @foreach($cat->items->where('in_archive', 1) as $item)
                            @if($item->count <= 0)

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
        @endif
    @endforeach

</div>

{{-- Переключатели для аналогов и уценки --}}
<script type="text/javascript" src="{{ asset('assets/js/togglers.js') }}"></script>
