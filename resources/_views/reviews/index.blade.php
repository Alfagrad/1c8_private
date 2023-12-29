@extends('layouts.new_app')

@section('content')

<div class="page">

    <div class="container">

        <div class="brad-crumbs">
            <a href="{{ asset('home') }}">Главная</a>
            »
            <span>Обзоры</span>
        </div>

        <h1>Обзоры</h1>

        @foreach($reviews as $r)

            <a href="{{ route('oneReview', ['alias' => $r->alias]) }}" class="news-block">

                <div class="image">

                    <img src="{{ asset('storage/'.$r->path_image) }}" alt="{{ $r->title }}">

                </div>

                <div class="content">

                    <div class="date">
                        {{ $r->created_at->format('d.m.Y') }}
                    </div>

                    <div class="name">
                        {{ $r->title }}
                    </div>

                    <div class="description">
                        {{ $r->description }}
                    </div>

                </div>

            </a>

        @endforeach

    </div>
</div>

@endsection
