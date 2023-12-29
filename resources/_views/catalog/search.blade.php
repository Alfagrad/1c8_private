@php 
    global $i;
    $GLOBALS['i'] = 0;
    $is_analog_line = 0; // метка для вывода аналогов
@endphp

<div class="search-results-status-title">
    Результаты поиска <strong>&laquo;{{ $searchKeyword }}&raquo;</strong>
</div>

{{-- Вывод товаров (не запчасти) --}}
@if($products_yes && $type == 'products')

<div class="w-table _toggled">

    {{-- Выдаем результат запроса по коду --}}
    @if(!empty($item_by_code))

    <div>

        <div class="row dropper">
            <h5 class="search-results-status-title">По коду</h5>
        </div>
        
        <table>
            <tbody>

                @php
                    // Переписываем для корректной работы инклюда
                    $item = $item_by_code;
                @endphp 

@include('catalog.snippets.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('catalog.snippets.tr_partial_analog')

            </tbody>

        </table>

    </div>

    @endif


    {{-- Выдаем товары, которые в наличии --}}
    @if($items_yes->count())

    @php
        $data_in_stock = $items_yes->where('count', '>', 0)->count();
        $data_soon = $items_yes->where('count_type', 3)->count();
        $data_reserve = $items_yes->where('count_type', 2)->count();
        $data_new_item = $items_yes->where('is_new_item', 1)->count();
        $data_action_item = $items_yes->where('is_action', 1)->count();
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

                        <td class="td-article"></td>

                        <td class="td-name">
                            В наличии
                        </td>

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

                @php $tmp = 1; @endphp

                @foreach($items_yes as $item)

@include('catalog.snippets.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('catalog.snippets.tr_partial_analog')

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

    @endif


    {{-- Выдаем уцененные товары --}}
    @if($items_yes_low_cost->count())

    @php
        $data_in_stock = $items_yes_low_cost->where('count', '>', 0)->count();
        $data_soon = $items_yes_low_cost->where('count_type', 3)->count();
        $data_reserve = $items_yes_low_cost->where('count_type', 2)->count();
        $data_new_item = $items_yes_low_cost->where('is_new_item', 1)->count();
        $data_action_item = $items_yes_low_cost->where('is_action', 1)->count();
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

                        <td class="td-article"></td>

                        <td class="td-name">
                            Уцененные
                        </td>

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

                @php $tmp = 1; @endphp

                @foreach($items_yes_low_cost as $item)

                    @if($item->is_component == 0)

@include('catalog.snippets.item_block_line')

                    @endif

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

    @endif


    {{-- Выдаем товары, которых нет в наличии --}}
    @if($items_no->count())

    @php
        $data_in_stock = $items_no->where('count', '>', 0)->count();
        $data_soon = $items_no->where('count_type', 3)->count();
        $data_reserve = $items_no->where('count_type', 2)->count();
        $data_new_item = $items_no->where('is_new_item', 1)->count();
        $data_action_item = $items_no->where('is_action', 1)->count();
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

                        <td class="td-article"></td>

                        <td class="td-name">
                            Нет в наличии
                        </td>

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

                @php $tmp = 1; @endphp

                @foreach($items_no as $item) 

@include('catalog.snippets.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('catalog.snippets.tr_partial_analog')
 
                @endforeach

                </tbody>

            </table>

        </div>

    </div>

    @endif


    {{-- Выдаем архивные товары --}}
    @if($archive->count() && $type == 'products')

    @php
        $data_in_stock = $archive->where('count', '>', 0)->count();
        $data_soon = $archive->where('count_type', 3)->count();
        $data_reserve = $archive->where('count_type', 2)->count();
        $data_new_item = $archive->where('is_new_item', 1)->count();
        $data_action_item = $archive->where('is_action', 1)->count();
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

                        <td class="td-name">
                            Архивные
                        </td>

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

                @php $tmp = 1; @endphp

                {{-- В наличии --}}
                @foreach($archive as $item)

                    @if($item->is_component == 0 && $item->count > 0)

@include('catalog.snippets.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('catalog.snippets.tr_partial_analog')

                    @endif

                @endforeach

                {{-- Нет в наличии --}}
                @foreach($archive as $item)

                    @if($item->is_component == 0 && $item->count <= 0)

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
@endif


{{-- Выводим запчасти --}}
@if($spares_yes && $type != 'products')

<div class="w-table _toggled">

    <div class="table-head">

            <table>
                <thead>
                    <tr>
                        <td class="td-image">
                            <div class="toggler-button"></div>
                        </td>

                        <td class="td-article"></td>

                        <td class="td-name">
                            Запчасти
                        </td>

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

{{--         <table>
            <thead>
            <tr>
                <td class="td-article">
                    <div class="toggler-button"></div>
                </td>
                <td class="td-name"></td>
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
        </table> --}}

    </div>

    <div class="table-body _toggled">

        {{-- Запчасти в наличии --}}
        <table>
            <tbody>
            @php $tmp = 1; @endphp
            @foreach($items_yes as $item)
                @if($item->is_component == 1)

@include('catalog.snippets.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('catalog.snippets.tr_partial_analog')

                @endif
            @endforeach
            </tbody>
        </table>

        {{-- Запчасти нет в наличии --}}
        <table>
            <tbody>
            @php $tmp = 1; @endphp
            @foreach($items_no as $item)

                @if($item->is_component == 1)

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

<!-- Всплывающее видео -->
<div id="modal_form"><!-- Сaмo oкнo -->
    <span class="modal_close"><img src="{{ asset('assets/img/close_youtube_ico.png') }}"></span> <!-- Кнoпкa зaкрыть -->
    <br>
    <center>
        <iframe width="650" height="488" src="" frameborder="0" allowfullscreen id='v1'></iframe>
    </center>
</div>
 
<div id="overlay"></div><!-- Пoдлoжкa -->

{{-- Переключатели для аналогов и уценки --}}
<script type="text/javascript" src="{{ asset('assets/js/togglers.js') }}"></script>
