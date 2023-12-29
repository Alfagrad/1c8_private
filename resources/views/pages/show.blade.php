@extends('layouts.app')

@section('content')

<div class="page">

    <div class="container">

        <article>

            <div class="brad-crumbs">
                <a href="{{ route('home.index') }}">Главная</a>
                &raquo;
                <span>{{ $article->title }}</span>
            </div>

            <h1>{{ $article->title }}</h1>

            {!! $article->content !!}

        </article>

    </div>

</div>

@endsection

@section('scripts')
@parent

@vite(['resources/js/service_toggler.js'])

@endsection
