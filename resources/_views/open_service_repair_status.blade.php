@extends('layouts.open')

@section('content')

<div class="open_service-repare-status-page">

    <div class="url-line">

        <div class="container">

            <a href="{{ asset('/') }}">Главная</a> - <span>Проверить статус ремонта</span>

        </div>

    </div>

    @include('general.repair-status-form')

</div>

@endsection

@section('scripts')
@parent

<script type="text/javascript" src="{{ asset('assets/js/get_repair.js') }}"></script>

@endsection