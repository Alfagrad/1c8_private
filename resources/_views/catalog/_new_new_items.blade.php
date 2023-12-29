@extends('layouts.new_app')

@section('content')

@php

    // видимость заголовков цен в каталожной выдаче **********
    if(isset($_COOKIE['opt_state'])) {
        if($_COOKIE['opt_state'] == 1) {
            $opt_style = "block";
        } else {
            $opt_style = "none";
        }
    } else {
        $opt_style = "block";
    }

    if(isset($_COOKIE['purcent_state'])) {
        if($_COOKIE['purcent_state'] == 1) {
            $purcent_style = "block";
        } else {
            $purcent_style = "none";
        }
    } else {
        $purcent_style = "block";
    }

    if(isset($_COOKIE['mr_state'])) {
        if($_COOKIE['mr_state'] == 1) {
            $mr_style = "block";
        } else {
            $mr_style = "none";
        }
    } else {
        $mr_style = "none";
    }

@endphp

<div class="catalog-page">
    <div class="container">

        @include('catalog.partials.new_left_menu')

        <div class="catalog-page_catalog-lines-section">

            @include('catalog.snippets.filters_line')  

            <div class="catalog-page_catalog-lines-block">

                <div class="catalog-lines-section_catalog-name-block">

                    <div class="catalog-lines-section_toggler-wrapper">

                        <div
                            class="catalog-lines-section_toggler js-catalog-toggler"
                            data-cat_index = "1"
                            title="Скрыть-показать товары"
                        >
                            -
                        </div>

                    </div>

                    <div class="catalog-lines-section_catalog-name">
                        Новые товары ({{ $new_items_count }})
                    </div>

                    <div class="catalog-lines-section_catalog-name-price js-opt-head" style="display: {{ $opt_style }};">
                        Цена BYN с НДС
                    </div>

                    <div class="catalog-lines-section_catalog-name-percent js-purcent-head" style="display: {{ $purcent_style }};">
                        Наценка дилера, %
                    </div>

                    <div class="catalog-lines-section_catalog-name-mr js-mr-head" style="display: {{ $mr_style }};">
                        МРЦ BYN
                    </div>

                    <div class="catalog-lines-section_catalog-name-end"></div>

                </div>

                <div class="items-line-block js-lines-block js-index-1">

                    @foreach($items as $item)

                        @include('catalog.snippets.item_lines')
                    
                    @endforeach

                </div>


            </div>
        </div>

    </div>
</div>

{{-- Всплывающее окно видео Youtube --}}
@include('general.popup_youtube')  

{{-- Всплывающее окно Хочу дешевле --}}
@include('general.popup_want_cheaper')

{{-- Всплывающее окно загрузки --}}
@include('general.popup_working')

@endsection

@section('scripts')
@parent

<script type="text/javascript" src="{{ asset('assets/js/category_handler.js').'?v='.config('constants.version') }}"> </script>
<script type="text/javascript" src="{{ asset('assets/js/togglers.js').'?v='.config('constants.version') }}"> </script>
<script type="text/javascript" src="{{ asset('assets/js/youtube.js').'?v='.config('constants.version') }}"> </script>
<script type="text/javascript" src="{{ asset('assets/js/want_cheaper.js').'?v='.config('constants.version') }}"> </script>
<script type="text/javascript" src="{{ asset('assets/js/catalog_items_toggler.js').'?v='.config('constants.version') }}"> </script>
<script type="text/javascript" src="{{ asset('assets/js/filters_line.js').'?v='.config('constants.version') }}"> </script>
<script type="text/javascript" src="{{ asset('assets/js/picture_zoom.js').'?v='.config('constants.version') }}"> </script>

@endsection