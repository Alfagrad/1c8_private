@extends('layouts.service')

@section('content')
    <body>
    <div class="b-wrapper p-index">

        @include('service.includes.header')
        @include('service.includes.nav')
 
        <section class="s-main-wrapper">
            <div class="container">
                <div class="wrapper">
                    <div class="w-main-table">
                        <div class="left">
                            @include('service.includes.leftMenu')
{{--                            @include('catalog.partials.leftMenuMobile')--}}
                        </div>

                        <div class="right" id="slider">
                            @include('service.includes.rightMenu')
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script>
            var use_local_storage = {{$useLocalStorage?1:0}};
            var search_category = {{(isset($searchCategory) and $searchCategory)?$searchCategory:0}};
            var without_refresh = {{isset($cancelRefresh)?$cancelRefresh:0}};

        </script>
        <div class="content preloader" style="display: none">
            <div class="w-circle">
                <div class="circle"></div>
                <div class="circle1"></div>
                <div class="circle2"></div>
            </div>
        </div>
    </div>


    {{-- @include('general.scripts') --}}


{{--     <script>

        $(window).on('resize load', function(){
            //index middle height-detect
            news_h = $('.w-main-new').height();
            sale_h = $('.w-main-sale').height();
            newitems_h = $('.w-new-items').height();
            reviews_h = $('.w-reviews').height();
            $('.p-index .b-news .w-news').css('max-height', news_h + sale_h - 50);
            $('.w-reviews, .w-new-items').css('max-height', (news_h + sale_h) / 2 - 50);

        });


    </script> --}}

    </body>
@endsection
