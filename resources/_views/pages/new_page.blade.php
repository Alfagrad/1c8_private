@extends('layouts.new_app')

@section('content')

<div class="page">

    <div class="container">

        <article>

            <div class="brad-crumbs">
                <a href="{{ asset('/home') }}">Главная</a>
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

<script type="text/javascript" src="{{ asset('assets/js/service_toggler.js') }}"> </script>

@endsection