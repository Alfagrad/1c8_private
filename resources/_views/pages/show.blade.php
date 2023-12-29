@extends('layouts.app')

@section('content')
    <body>
    <div class="b-wrapper p-article">
        @include('general.header')
        @include('general.nav')
        @include('general.bcrumb')
        <section class="s-main-wrapper article">
            <div class="container">
                <div class="wrapper white-bg-wrapper">
                    <article>
                        {!! $article->content !!}
                    </article>
            </div>
    </div>
    </section>
        @include('general.footer')
        @include('general.popups')
    </div>
    @include('general.scripts')
    </body>
@endsection
