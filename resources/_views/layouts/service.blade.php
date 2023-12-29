<!DOCTYPE html>
<html lang="ru">
<head>

    {{-- version --}}
    <?php $version = date('Ymd'); ?>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
    <meta name="format-detection" content="telephone=no" />
    <meta http-equiv="x-rim-auto-match" content="none" />
    <meta name="msapplication-TileColor" content="#ffffff" />
    <meta name="msapplication-TileImage" content="" />
    <meta name="theme-color" content="#ffffff" />
    {{-- <meta http-equiv="Cache-Control" content="no-cache"> --}}
    <meta http-equiv="Cache-Control" content="max-age=86400, must-revalidate">
    @if(isset($seo))
        <title>{{$seo->title or 'Alfastok.by'}}</title>
        <meta name="keywords" content="{{$seo->keywords or ''}}">
        <meta name="description" content="{{$seo->description or ''}}">
        <meta name="title" content="{{$seo->title or 'Alfastok.by'}}">
    @else
        <title>Alfastok.by</title>
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="title" content="Оборудование">
    @endif

    <link rel="shortcut icon" type="image/png" href="{{ asset('fav.ico') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendors/bxslider/jquery.bxslider.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/formstyler/jquery.formstyler.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/mCustomScrollbar/jquery.mCustomScrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/fancybox/jquery.fancybox.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/fancybox/jquery.fancybox-buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/sweetalert/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/common.css?'). $version  }}">
    <link rel="stylesheet" href="{{ asset('assets/css/edition.css?'). $version }}">
    <link rel="stylesheet" href="{{ asset('assets/css/service.css?'). $version }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/youtube.css?'). $version }}"> --}}

    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-1.11.1.min.js" integrity="sha256-VAvG3sHdS5LqTT+5A/aeq/bZGa/Uj04xKxY8KM/w9EE=" crossorigin="anonymous"></script>

</head>
<body>

@include('general.top_alert_service')

@yield('content')

    <div class="b-wrapper p-index">

        @include('service.includes.footer')
        @include('general.popups')

    </div>


<script type="text/javascript" src="{{ asset('assets/js/jquery.min.js') }}"> </script>
<script type="text/javascript" src="{{ asset('assets/js/main_srv.js?'). $version  }}"> </script>
{{-- <script type="text/javascript" src="{{ asset('assets/js/catalog_srv.js?'). $version  }}"> </script> --}}
<script type="text/javascript" src="{{ asset('assets/js/cart.js?'). $version  }}"> </script>

<script type="text/javascript" src="{{ asset('assets/vendors/bxslider/jquery.bxslider.min.js') }}"> </script>

@section('scripts')
@show


    <script type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
    <script type="text/javascript" src="https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
    <script type="text/javascript" src="https://yastatic.net/share2/share.js"></script>


    <script type="text/javascript" src="{{ asset('assets/js/vendor/cloudzoom.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.zoom.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/vendors/formstyler/jquery.formstyler.min.js') }}"> </script>

    <script type="text/javascript" src="{{ asset('assets/vendors/fancybox/jquery.fancybox.pack.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/vendors/fancybox/jquery.fancybox-buttons.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/vendors/bxslider/jquery.bxslider.min.js') }}"> </script>

    <script type="text/javascript" src="{{ asset('assets/vendors/mCustomScrollbar//jquery.mCustomScrollbar.concat.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/vendors/jquery.validate.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/vendors/jquery.maskedinput.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/vendors/sweetalert/sweetalert.min.js') }}"> </script>

    <script type="text/javascript" src="{{ asset('assets/vendors/jquery.qtip.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/vendors/jquery.qtip.css') }}">


<script type="text/javascript" src="{{ asset('assets/js/youtube.js') }}"> </script>

    <script type="text/javascript" src="{{ asset('assets/js/lib.js') }}"> </script>

    <script type="text/javascript" src="{{ asset('assets/js/zoomsl.js?'). $version  }}"> </script>

    <script>
        var popup_if_expensite_title = "{{setting('popup_if_expensite_title')}}";
        var popup_if_expensite_text = "{{setting('popup_if_expensite_text')}}";
    </script>

    @if( Route::currentRouteName() ==  'registrationView')
        <script type="text/javascript" src="{{ asset('assets/js/auth.js?'). $version  }}"> </script>
    @endif

    @if( Route::currentRouteName() ==  'profileIndex')
        <script type="text/javascript" src="{{ asset('assets/js/profile.js?'). $version  }}"> </script>
    @endif

    @if( Route::currentRouteName() ==  'profileRepairs')
        <script type="text/javascript" src="{{ asset('assets/js/repair.js?') }}"> </script>
    @endif
{{--     @if( Route::currentRouteName() ==  'catalogView' or Route::currentRouteName() ==  'catalogCurrentCategory' or Route::currentRouteName() ==  'catalogSearch')
        <script type="text/javascript" src="{{ asset('assets/js/catalog.js?'). $version  }}"> </script>

    @endif --}}

    <script type="text/javascript" src="{{ asset('assets/vendors/tipsy/jquery.tipsy.js') }}"> </script>
    <link rel="stylesheet" href="{{ asset('assets/vendors/tipsy/jquery.tipsy.css') }}">

    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>

    <script type="text/javascript" src="{{ asset('assets/js/bootstrapCollapse.min.js') }}"> </script>

    <script type="text/javascript">
        jQuery.browser = {};
        (function () {
            jQuery.browser.msie = false;
            jQuery.browser.version = 0;
            if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
                jQuery.browser.msie = true;
                jQuery.browser.version = RegExp.$1;
            }
        })();
    </script>

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            var div = document.querySelector('#slider');

            var storageKey = 'scroll_' + window.location.pathname;


                if(localStorage.getItem('catalog') === window.location.pathname){
                div.scrollTop = localStorage.getItem(storageKey)||0;
                div.addEventListener('scroll', function() {
                    localStorage.setItem(storageKey, div.scrollTop);
                })
            }
            let str = window.location.pathname;
            let regexp = /catalog\/\d+/;
            var catalog = regexp.exec(str);
            if (catalog)   localStorage.setItem('catalog', str);

        });





    </script>

    <script type="text/javascript" src="{{ asset('assets/js/top_alert.js') }}"> </script>



</body>
</html>


