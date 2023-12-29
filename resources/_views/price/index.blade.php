@extends('layouts.new_app')

@section('content')

<div class="page">

    <div class="container">

        <div class="make-price-page">

            <div class="brad-crumbs">
                <a href="{{ asset('home') }}">Главная</a>
                »
                <span>Скачать прайс</span>
            </div>

            <h1>Скачать прайс</h1>

            <h2>Выберите категории для выгрузки:</h2>

            <div class="make-price-page_category-choice-block js-all-category-block">

                <label class="category-line all">

                    <div class="input js-all-category">
                        <input type="checkbox" name="all" checked>
                    </div>

                    <div class="name">
                        Все
                    </div>

                </label>

                <form method="post" action="{{ route('price.download') }}" target="_blanc">

                    {{ csrf_field() }}

                    @foreach($categories as $category)

                        <div class="js-category-block">

                            <label class="category-line">

                                <div class="input js-category">
                                    <input
                                        class="js-for-all"
                                        type="checkbox"
                                        name="cat_info[]"
                                        value="{{ $category->id_1c }};{{ $category->name }}"
                                        checked>
                                </div>

                                <div class="name">
                                    {{ $category->name }}
                                </div>

                                <div class="arrow js-arrow" title="Развернуть">
                                    @include('svg.phones_arrow_ico')
                                </div>

                            </label>

                            @if($category->subCategory->count())

                                @foreach($category->subCategory as $sub_cat)

                                    <div class="sub-category-block js-sub-category-block">

                                        <label class="category-line sub-category">

                                            <div class="input js-sub-category">
                                                <input
                                                    class="js-for-all js-for-category"
                                                    type="checkbox"
                                                    name="cat_info[]"
                                                    value="{{ $sub_cat->id_1c }};{{ $sub_cat->name }}"
                                                    checked
                                                >
                                            </div>

                                            <div class="name">
                                                {{ $sub_cat->name }}
                                            </div>

                                        </label>

                                        @if($sub_cat->subCategory->count())

                                            @foreach($sub_cat->subCategory as $sub_sub_cat)

                                                <div class="js-sub-sub-category-block">

                                                    <label class="category-line sub-sub-category">

                                                        <div class="input js-sub-sub-category">
                                                            <input
                                                                class="js-for-all js-for-category js-for-sub-category"
                                                                type="checkbox" name="cat_info[]"
                                                                value="{{ $sub_sub_cat->id_1c }};{{ $sub_sub_cat->name }}"
                                                                checked
                                                            >
                                                        </div>

                                                        <div class="name">
                                                            {{ $sub_sub_cat->name }}
                                                        </div>

                                                    </label>

                                                    @if($sub_sub_cat->subCategory->count())

                                                        @foreach($sub_sub_cat->subCategory as $sub_sub_sub_cat)

                                                            <label class="category-line sub-sub-sub-category">

                                                                <div class="input js-sub-sub-sub-category">
                                                                    <input
                                                                        class="js-for-all js-for-category js-for-sub-category js-for-sub-sub-category"
                                                                        type="checkbox" name="cat_info[]"
                                                                        value="{{ $sub_sub_sub_cat->id_1c }};{{ $sub_sub_sub_cat->name }}"
                                                                        checked
                                                                    >
                                                                </div>

                                                                <div class="name">
                                                                    {{ $sub_sub_sub_cat->name }}
                                                                </div>

                                                            </label>

                                                        @endforeach

                                                    @endif

                                                </div>

                                            @endforeach

                                        @endif

                                    </div>

                                @endforeach

                            @endif

                        </div>

                    @endforeach

                    <div class="button-line">
                        <button type="submit" name="price_excel">
                            Скачать Excel
                        </button>

                        <button type="submit" name="price_yml">
                            Скачать YML
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

</div>

@endsection

@section('scripts')
@parent

@vite(['resources/js/price_category.js'])

{{--<script type="text/javascript" src="{{ asset('assets/js/price_category.js').'?v='.config('constants.version')  }}"> </script>--}}


@endsection
