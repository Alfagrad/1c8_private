@extends('layouts.app')

@section('content')
    <div class="b-wrapper p-catalog small">
        @include('catalog.partials.header')
        @include('catalog.partials.menu')


        <div class="empty-header"></div>
        <!--MAIN WRAPPER-->
        <section class="s-main-wrapper">
            <div class="container">
                <div class="wrapper">
                    <div class="w-main-table">
                        <div class="left">
                            @include('catalog.partials.leftMenu')
{{--                            @include('catalog.partials.leftMenuMobile')--}}
                        </div>

                        <div class="right" id="slider">

                            <div class="catalog-page_filter-line">

                                <div class="catalog-page_filter-line_catalog-toggler">

                                    <div class="wrapper w-table-check-all" style="display: block;">

                                        <div class="toggler-button _minus" ></div>Cвернуть все

                                    </div>

                                    <div class="wrapper w-table-uncheck-all" style="display: none">

                                        <div class="toggler-button _plus " ></div>Развернуть все

                                    </div>

                                </div>

                                <div class="catalog-page_filter-line_show-str">Фильтры по товару:</div>

                                <div class="button filter_all _active" title="Показать все товары">
                                    Все
                                </div>

                                {{-- Если в поиске запчасти, скрываем кнопку "В архиве" --}}
                                @php
                                    if(isset($type) && $type == 'spares') $archive_button_hidden = "_not";
                                        else $archive_button_hidden = "";
                                @endphp

                                <div class="button filter_archive {!! $archive_button_hidden !!}" title="Показать архивные товары">
                                    +Архив
                                </div>

                                <div class="catalog-page_filter-line_v-line"></div>

                                <div class="button filter_in-stock" title="Показать товары в наличии">
                                    В наличии
                                </div>

                                <div class="button filter_soon" title="Показать товары которые скоро поступят">
                                    В пути
                                </div>

                                <div class="button filter_reserve" title="Показать отложенные товары">
                                    В резерве
                                </div>

                            </div>

                            @include('catalog.partials.rightMenu')
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script>
            var use_local_storage = {{$useLocalStorage?1:0}};
            var search_category = {{(isset($searchCategory) and $searchCategory)?$searchCategory:0}};
            var without_refresh = {{isset($cancelRefresh)?$cancelRefresh:0}};

        </script>
        <div class="content preloader" style="display: none">
            <div class="w-circle">
                <div class="circle"></div>
                <div class="circle1"></div>
                <div class="circle2"></div>
            </div>
        </div>
        @include('general.popups')
    </div>

{{-- Всплывающее окно видео Youtube --}}
@include('general.popup_youtube')  


    @include('general.scripts')

<script type="text/javascript" src="{{ asset('assets/js/filters.js') }}"> </script>

@endsection