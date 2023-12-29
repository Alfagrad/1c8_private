@extends('layouts.open')

@section('content')

<div class="open_contact-page">

    <div class="url-line">

        <div class="container">

            <a href="{{ asset('/') }}">Главная</a> - <span>Адреса сервисных центров</span>

        </div>

    </div>

    <div class="open_contact-page_services">

        <div class="container">

            <h2>Адреса и контактные телефоны сервисных центров</h2>

            {!! $service->content !!}

        </div>

    </div>


</div>

@endsection

@section('scripts')
@parent

@vite(['resources/js/jquery.js', 'resources/js/service_toggler.js'])

{{--<script type="text/javascript" src="{{ asset('assets/js/service_toggler.js') }}"></script>--}}

@endsection
