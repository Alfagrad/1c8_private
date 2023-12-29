@extends('layouts.app')

@section('content')

<div class="page">

    <div class="container">

        <div class="brad-crumbs">
            <a href="{{ route('home.index') }}">Главная</a>
            »
            <a href="{{ route('news.index') }}">Новости</a>
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
