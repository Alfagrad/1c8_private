@extends('layouts.app')

@php

    // видимость заголовков цен в каталожной выдаче **********
    if (isset($_COOKIE['opt_state'])) {
        if ($_COOKIE['opt_state']) {
            $opt_style = 'block';
        } else {
            $opt_style = 'none';
        }
    } else {
        $opt_style = 'block';
    }

    if (isset($_COOKIE['purcent_state'])) {
        if ($_COOKIE['purcent_state']) {
            $purcent_style = 'block';
        } else {
            $purcent_style = 'none';
        }
    } else {
        $purcent_style = 'block';
    }

    if (isset($_COOKIE['mr_state'])) {
        if ($_COOKIE['mr_state']) {
            $mr_style = 'block';
        } else {
            $mr_style = 'none';
        }
    } else {
        $mr_style = 'none';
    }

@endphp

@section('content')

    <div class="catalog-page">
        <div class="container">

            @include('includes.left_menu')

            <div class="catalog-page_catalog-lines-section">

                @include('includes.filters_line')

                <div class="catalog-page_catalog-lines-block">

                    <div class="breadcrumbs">

                        <a href="{{ route('home.index') }}" title="Переход на Главную страницу">
                            Главная
                        </a>
                        <span>|</span>

                        @foreach ($breadcrumbs ?? [] as $breadcrumb)
                            @if (!$loop->last)
                                <a
                                    @if (!$loop->first) href="{{ route('catalogue.index' , $breadcrumb['id']) }}"
                                    title="Переход в категорию {{ $breadcrumb['name'] }}" @endif>
                                    {{ $breadcrumb['name'] }}
                                </a>
                                <span>→</span>
                            @else
                                <div class="no_link">
                                    {{ $breadcrumb['name'] }}
                                </div>
                            @endif
                        @endforeach

                    </div>

                    @php
                        $cat_index = 1;
                    @endphp

                    @foreach ($sub_categories ?? [] as $cat)
                        @php
                            if ($cat['is_archive_cat']) {
                                $archive_cat_class = 'is_archive_cat';
                            } else {
                                $archive_cat_class = '';
                            }
                        @endphp

                        <div class="catalog-lines-section_catalog-name-block-wrapper js-cat-header {{ $archive_cat_class }}">

                            <div class="catalog-lines-section_catalog-name-block">

                                <div class="catalog-lines-section_toggler-wrapper">

                                    <div class="catalog-lines-section_toggler js-catalog-toggler"
                                         data-cat_index="{{ $cat_index }}"
                                         title="Скрыть-показать товары категории - {{ $cat['name'] }}">
                                        -
                                    </div>

                                </div>

                                <div class="catalog-lines-section_catalog-name">
                                    {{ $cat['name'] }}
                                </div>

                                <div class="catalog-lines-section_catalog-name-price js-opt-head"
                                     style="display: {{ $opt_style }};">
                                    @yield('title-price')

                                </div>

                                <div class="catalog-lines-section_catalog-name-percent js-purcent-head"
                                     style="display: {{ $purcent_style }};">
                                    @yield('title-purcent')
                                </div>

                                <div class="catalog-lines-section_catalog-name-mr js-mr-head"
                                     style="display: {{ $mr_style }};">
                                    @yield('title-mr-price')

                                </div>

                                <div class="catalog-lines-section_catalog-name-end"></div>

                            </div>

                        </div>

                        <div
                            class="items-line-block js-lines-block js-index-{{ $cat_index }} {{ $archive_cat_class }}">

                            @foreach ($cat['items'] as $item)
                                @include('item.line.'.$view)
{{--                                @include('catalog.snippets.item_lines')--}}
                            @endforeach


                        </div>

                        @php($cat_index++)
                    @endforeach

                    @yield('items')

                </div>
            </div>

        </div>
    </div>

    {{-- Всплывающее окно видео Youtube --}}
    @include('includes.popups.youtube')

    {{-- Всплывающее окно Хочу дешевле --}}
    @include('includes.popups.want_cheaper')

    {{-- Всплывающее окно загрузки --}}
    @include('includes.popups.working')
@endsection

@section('scripts')
    @parent

    @vite(['resources/js/unify_catalog.js'])

@endsection
