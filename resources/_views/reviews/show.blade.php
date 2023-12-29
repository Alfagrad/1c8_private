@extends('layouts.new_app')

@section('content')

<div class="page">

    <div class="container">

        <div class="brad-crumbs">
            <a href="{{ asset('home') }}">Главная</a>
            »
            <a href="{{ asset('reviews') }}">Обзоры</a>
            »
            <span>{{ $review->title }}</span>
        </div>

        <h1>{{ $review->title }}</h1>

        <div class="news-content">
            {!! $review->content !!}
        </div>

    </div>
</div>

@endsection
