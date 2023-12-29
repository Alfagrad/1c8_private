@extends('layouts.open')

@section('content')

<div class="open_contact-page">

    <div class="url-line">

        <div class="container">

            <a href="{{ asset('/') }}">Главная</a> - <span>Ремонты, брак, документы СЦ</span>

        </div>

    </div>

    <div class="open_contact-page_services">
        
        <div class="container">

            <h2>Ремонты, брак, документы СЦ</h2>

            {!! $service->content !!}

        </div>

    </div>


</div>

@endsection

@section('scripts')
@parent

<script type="text/javascript" src="{{ asset('assets/js/service_toggler.js') }}"></script>

@endsection