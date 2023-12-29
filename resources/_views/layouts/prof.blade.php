<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">-->
    <meta name="format-detection" content="telephone=no" />
    <meta http-equiv="x-rim-auto-match" content="none" />
    <meta name="msapplication-TileColor" content="#ffffff" />
    <meta name="msapplication-TileImage" content="" />
    <meta name="theme-color" content="#ffffff" />
    @if(isset($seo))
        <title>{{$seo->title or 'Alfastok.by'}}</title>
        <meta name="keywords" content="{{$seo->keywords or 'Alfastok.by'}}">
        <meta name="description" content="{{$seo->description or 'Alfastok.by'}}">
        <meta name="title" content="{{$seo->title or 'Alfastok.by'}}">
    @else
        <title>Alfastok.by</title>
        <meta name="keywords" content="Оборудование">
        <meta name="description" content="Оборудование">
        <meta name="title" content="Оборудование">
    @endif

    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.ico') }}">

    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

    <link rel="stylesheet" href="{{ asset('assets/vendors/bxslider/jquery.bxslider.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/formstyler/jquery.formstyler.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/mCustomScrollbar/jquery.mCustomScrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/fancybox/jquery.fancybox.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/fancybox/jquery.fancybox-buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/sweetalert/sweetalert.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/common.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/edition.css') }}">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <script type="text/javascript" src="http://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
    <script type="text/javascript" src="http://yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
    <script type="text/javascript" src="http://yastatic.net/share2/share.js"></script>


    <script type="text/javascript" src="{{ asset('assets/js/vendor/cloudzoom.js') }}"> </script>

    <script type="text/javascript" src="{{ asset('assets/vendors/formstyler/jquery.formstyler.min.js') }}"> </script>

    <script type="text/javascript" src="{{ asset('assets/vendors/fancybox/jquery.fancybox.pack.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/vendors/fancybox/jquery.fancybox-buttons.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/vendors/bxslider/jquery.bxslider.min.js') }}"> </script>

    <script type="text/javascript" src="{{ asset('assets/vendors/mCustomScrollbar//jquery.mCustomScrollbar.concat.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/vendors/jquery.validate.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/vendors/jquery.maskedinput.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/vendors/sweetalert/sweetalert.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/js/lib.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/js/main.js') }}"> </script>

    <script>
        var popup_if_expensite_title = "{{setting('popup_if_expensite_title')}}";
        var popup_if_expensite_text = "{{setting('popup_if_expensite_text')}}";
    </script>
    @if( Route::currentRouteName() ==  'registrationView')
        <script type="text/javascript" src="{{ asset('assets/js/auth.js') }}"> </script>
    @endif

    @if( Route::currentRouteName() ==  'profileIndex')
        <script type="text/javascript" src="{{ asset('assets/js/profile.js') }}"> </script>
    @endif

    @if( Route::currentRouteName() ==  'profileRepairs')
        <script type="text/javascript" src="{{ asset('assets/js/repair.js') }}"> </script>
    @endif
    @if( Route::currentRouteName() ==  'catalogView' or Route::currentRouteName() ==  'catalogCurrentCategory')
        <script type="text/javascript" src="{{ asset('assets/js/catalog.js') }}"> </script>
        <script type="text/javascript" src="{{ asset('assets/vendors/jquery.qtip.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('assets/vendors/jquery.qtip.css') }}">
    @endif
    <script type="text/javascript" src="{{ asset('assets/js/cart.js') }}"> </script>

    <script type="text/javascript" src="{{ asset('assets/js/wheelzoom.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('assets/js/bootstrapCollapse.min.js') }}"> </script>


</head>


@yield('content')

</html>


