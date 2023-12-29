@php 
    global $i;
    $GLOBALS['i'] = 0;
    $is_analog_line = 0; // метка для вывода аналогов
@endphp

{{-- Вывод товаров (не запчасти) --}}
@if($products_yes && $type == 'products')
    <div class="w-table _toggled">
        <h5 class="search-results-status-title">Товары</h5>
        <div class="table-head">
            <table>
                <thead>
                <tr>
                    <td class="td-image">
                        <div class="toggler-button"></div>
                    </td>

                    <td class="td-article">
                    </td>
                    <td class="td-name"></td>
                    <td class="td-sale"></td>
                    <td class="td-pcs"></td>
                    <td class="td-valible"></td>
                </tr>
                </thead>
            </table>
        </div>
        <div class="table-body _toggled">


            {{-- Выдаем результат запроса по коду --}}
            <table>
                <tbody>

                @if(!empty($item_by_code))

                    @php

                        // Переписываем для корректной работы инклюда
                        $item = $item_by_code;

                    @endphp 

                    <div class="row dropper"><h5 class="search-results-status-title">По коду</h5></div>

@include('service.includes.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('service.includes.tr_partial_analog')

                @endif

                </tbody>

            </table>

            {{-- Выдаем товары, которые в наличии --}}
            <table>
                <tbody>

                @php $tmp = 1; @endphp

                @if($items_yes->count())

                    <div class="row dropper"><h5 class="search-results-status-title">В наличии</h5></div>

                @endif

                @foreach($items_yes as $item)


@include('service.includes.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('service.includes.tr_partial_analog')


                @endforeach
                </tbody>
            </table>

            {{-- Выдаем уцененные товары --}}
            <table>
                <tbody>
                @php $tmp = 1; @endphp
                @if($items_yes_low_cost->count())
                    <div class="row dropper"><h5 class="search-results-status-title">Уцененные</h5></div>@endif
                @foreach($items_yes_low_cost as $item)
                    @if($item->is_component == 0)

@include('service.includes.item_block_line')

                    @endif
                @endforeach
                </tbody>
            </table>

            {{-- Выдаем товары, которых нет в наличии --}}
            <table>
                <tbody>
                @php $tmp = 1; @endphp
                @if($items_no->count())
                    <div class="row dropper"><h5 class="search-results-status-title">Нет в наличии</h5></div>
                @endif
                @foreach($items_no as $item) 

@include('service.includes.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('service.includes.tr_partial_analog')
 
                @endforeach
                </tbody>
            </table>

            {{-- Выдаем архивные товары --}}
            <table>
                <tbody>
                @php $tmp = 1; @endphp
                @if($archive->count() && $type == 'products')
                    <div class="row dropper"><h5 class="search-results-status-title">Архивные</h5></div>@endif
                {{-- В наличии --}}
                @foreach($archive as $item)
                    @if($item->is_component == 0 && $item->count > 0)

@include('service.includes.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('service.includes.tr_partial_analog')

                    @endif
                @endforeach
                {{-- Нет в наличии --}}
                @foreach($archive as $item)
                    @if($item->is_component == 0 && $item->count <= 0)

@include('service.includes.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('service.includes.tr_partial_analog')

                    @endif
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endif


{{-- Выводим запчасти --}}
@if($spares_yes && $type != 'products')
    <div class="w-table _toggled">
        <h5 class="search-results-status-title">Запчасти</h5>
        <div class="table-head">
            <table>
                <thead>
                <tr>
                    <td class="td-article">
                        <div class="toggler-button"></div>
                    </td>
                    <td class="td-name"></td>
                    <td class="td-more"></td>
                    <td class="td-pcs"></td>
                    <td class="td-valible"></td>
                </tr>
                </thead>
            </table>
        </div>
        <div class="table-body _toggled">

            {{-- Запчасти в наличии --}}
            <table>
                <tbody>
                @php $tmp = 1; @endphp
                @foreach($items_yes as $item)
                    @if($item->is_component == 1)

@include('service.includes.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('service.includes.tr_partial_analog')

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

@include('service.includes.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('service.includes.tr_partial_analog')

                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
{{-- Переключатели для аналогов и уценки --}}
<script type="text/javascript" src="{{ asset('assets/js/togglers.js') }}"></script>

