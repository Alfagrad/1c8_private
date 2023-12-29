@extends('layouts.new_app')

@section('content')

<div class="page">

    <div class="container">

        <div class="brad-crumbs">
            <a href="{{ asset('home') }}">Главная</a>
            »
            <a href="{{ asset('news') }}">Новости</a>
            »
            <span>{{ $news->title }}</span>
        </div>

        <h1>{{ $news->title }}</h1>

        <div class="news-content">
            {!! $news->content !!}
        </div>

    </div>
</div>

@endsection
