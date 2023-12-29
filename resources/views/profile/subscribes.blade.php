@extends('layouts.app')

@section('content')

<div class="page">

    <div class="container">

        <div class="cabinet-page">

            @include('profile.menu')

            <h1>Подписки на рассылки</h1>

            @php
                if(isset($note)) {
                    $disp = "display";
                } else {
                    $note = "";
                    $disp = "none";
                }
            @endphp

            <div style="color: red; margin: 10px 0 20px; display: {{ $disp }}">{{ $note }}</div>

            <form class="subscribes-form" method="post" action="{{ asset('profile/subscribes/save') }}">

                {{ csrf_field() }}

                <label>
                    <input type="checkbox" name="xls_weekly" value="1" @if($subscribe->xls_weekly) checked @endif />
                    Получать прайс лист XLS еженедельно
                </label>
                <label>
                    <input type="checkbox" name="news" value="1" @if($subscribe->news) checked @endif />
                    Рассылка новостей и акций
                </label>
                <label>
                    <input type="checkbox" name="new_items" value="1" @if($subscribe->new_items) checked @endif />
                    Получать уведомления о поступлении товаров
                </label>
                <label>
                    <input type="checkbox" name="copy_order" value="1" @if($subscribe->copy_order) checked @endif />
                    Получать копию заказов на E-mail
                </label>

                <button type="submit" class="submit-button">Отправить</button>

            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent

<script type="text/javascript" src="{{ asset('assets/js/new_profile.js') }}"></script>

@endsection
