@extends('layouts.service')

@section('content')

    <div class="b-wrapper p-article">

        @include('service.includes.header')
        @include('service.includes.nav')

        <section class="s-main-wrapper article" style="margin-bottom: 5px;">
            <div class="container">
                <div class="wrapper white-bg-wrapper">
                    <article>
                        <h1>Благодарим за заказ!</h1>
                        {!! $article->content !!}
                    </article>
                </div>
            </div>
        </section>
    </div>

@endsection
