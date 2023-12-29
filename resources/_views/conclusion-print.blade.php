@extends('layouts.new_app')

@section('content')

<div class="сonclusion-print-page">
    <div class="container">

        <h1>Здравствуйте {{ Auth::user()->name }}</h1>

        @if(Auth::user()->role_id != '8')

            <p>Похоже, Вы заблудились...</p>

        @else

            @php
                if(session('order_id_buh')){
                    $order_id = session('order_id_buh');
                    $style_str = "style='display: block;'";
                    $data_buh = 1;
                } else {
                    $order_id = '';
                    $style_str = '';
                    $data_buh = '';
                }
            @endphp

            <div class="search-header">
                Введите номер заключения, нажмите Найти:
            </div>

            <div class="search-form">
                <input type="text" name="сonclusion_num" value="{{ $order_id }}">
                <button name="search_butt" class="js-search-button" data-buh="{{ $data_buh }}">
                    Найти
                </button>

                <div class="form">
                    <form class="js-edit-conclusion" {!! $style_str !!}>
                        <input type="hidden" name="order_id" value="{{ $order_id }}">
                        <input type="hidden" name="buh" value="1">
                        <button type="submit" class="js-edit-button">Редактировать</button>
                    </form>
                </div>

                <div class="form">
                    <form method="post" action="{{ asset('/service/download-conclusion') }}" class="js-download-conclusion" {!! $style_str !!}>
                        {{ csrf_field() }}
                        <input type="hidden" name="order_id" value="{{ $order_id }}">
                        <button type="submit">Скачать</button>
                    </form>
                </div>


            </div>

            <div class="result-block js-result-block"></div>

        @endif

    </div>
</div>

@endsection


@section('scripts')
@parent

<script type="text/javascript" src="{{ asset('assets/js/print_conclusion.js') }}"></script>

@endsection