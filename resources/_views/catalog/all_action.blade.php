@php 
    global $i;
    $GLOBALS['i'] = 0;
    $is_analog_line = 0; // метка для вывода аналогов
@endphp

@if($items->where('in_archive', 0)->count())

<div class="w-table _toggled">

    <div class="table-head">
        <table>
            <thead>
            <tr>
                <td class="td-image">
                    <div class="toggler-button"></div>
                </td>

                <td class="td-article">
                </td>
                <td class="td-name">Акции</td>
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

            @foreach($items->where('in_archive', 0) as $item)

@include('catalog.snippets.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('catalog.snippets.tr_partial_analog')

            @endforeach
            </tbody>
        </table>

    </div>
</div>

@endif

@if($items->where('in_archive', 1)->count())

<div class="w-table _toggled js-archive-items" style="display: none;">

    <div class="table-head">
        <table>
            <thead>
            <tr>
                <td class="td-image">
                    <div class="toggler-button"></div>
                </td>

                <td class="td-article">
                </td>
                <td class="td-name">Акции (в архиве)</td>
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

            @foreach($items->where('in_archive', 1) as $item)

@include('catalog.snippets.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('catalog.snippets.tr_partial_analog')

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
