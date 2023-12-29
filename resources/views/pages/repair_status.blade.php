@extends('layouts.app')

@section('content')

<div class="open_service-repare-status-page">

    <div class="url-line">

        <div class="container brad-crumbs">

            <a href="{{ route('home.index') }}">Главная</a> &raquo; <span>Проверить статус ремонта</span>

        </div>

    </div>

    @include('includes.forms.repair-status')

</div>

@endsection

@section('scripts')
@parent

@vite(['resources/js/get_repair.js'])

@endsection
