@extends('layouts.new_app')

@section('content')

<div class="page">

    <div class="container">

        <div class="brad-crumbs">
            <a href="{{ asset('home') }}">Главная</a>
            »
            <span>Новости</span>
        </div>

        <h1>Новости</h1>

        @foreach($news as $n)

            <a href="{{ $n->getRouteName() }}" class="news-block">

                <div class="image">

                    <img src="{{ Storage::disk('local2')->url($n->path_image) }}" alt="{{ $n->title }}">

                </div>

                <div class="content">

                    <div class="date">
                        {{ $n->created_at->format('d.m.Y') }}
                    </div>

                    <div class="name">
                        {{ $n->title }}
                    </div>

                    <div class="description">
                        {{ $n->description }}
                    </div>

                </div>

            </a>

        @endforeach

    </div>
</div>

@endsection
