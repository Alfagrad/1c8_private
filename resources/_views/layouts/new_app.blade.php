<!DOCTYPE html>
<html lang="ru">
<head>

@php
    // версия для кэшируемых скриптов
    // $version = 'v='.date('Ymd');
    $version = config('constants.version');

    //если находимся в корзине, обновляем страницу каждые 15 мин
    $url = $_SERVER['REQUEST_URI'];
    $url = explode('?', $url);
    $url = $url[0];
    if($url == "/cart-page") {
        echo "

    <meta http-equiv='Refresh' content='900' />
        ";
    }

@endphp

    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Alfastok.by</title>
    <meta name="description" content="Компания Альфасад - первый импортер в Республику Беларусь садовой техники, строительного оборудования и инструмента торговых марок Brado, Skiper, Katana, Spec, Darc, Welt.">
    <meta name="title" content="Alfastok.by">

    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.ico') }}">

{{--    <link rel="stylesheet" href="{{ asset('assets/css/styles.css').'?v='.config('constants.version') }}">--}}
    @vite(['resources/scss/app.scss', 'resources/scss/private.scss'])
{{--    <link rel="stylesheet" href="{{ asset('assets/css/adaptive.css').'?v='.config('constants.version') }}">--}}

@section('css')
@show

</head>
<body>

<livewire:messages />

@if(profile()->isDealer())
    @include('general.headers.dealer')
@else
    @include('general.headers.service')
@endif

{{--@include('general.header-new')--}}
@include('general.nav-new')

@yield('content')

@include('general.footer-new')
@include('general.mail_popup')

@vite(['resources/js/app.js', 'resources/js/jquery.js', 'resources/js/jquery.cookie.js', 'resources/js/home.js'])
{{--@vite(['resources/js/jquery.js', 'resources/js/jquery.cookie.js', 'resources/js/header_phones_handler.js', 'resources/js/main_menu_handler.js', 'resources/js/search_handler.js', 'resources/js/header_close_mobile_ico_handler.js', 'resources/js/cart.js', 'resources/js/new_cart_counts.js'])--}}

{{--<script type="text/javascript" src="{{ asset('assets/js/jquery.js') }}"> </script>--}}
{{--<script type="text/javascript" src="{{ asset('assets/js/jquery.cookie.js') }}"> </script>--}}
<script type="text/javascript">
    // начальные установки кук для отображения цен
    // if(!$.cookie('opt_state') || !$.cookie('purcent_state') || !$.cookie('mr_state')) {
    //     if(!$.cookie('opt_state')) {
    //         $.cookie('opt_state', 1, {expires: 7, path: '/'});
    //     }
    //     if(!$.cookie('purcent_state')) {
    //         $.cookie('purcent_state', 1, {expires: 7, path: '/'});
    //     }
    //     if(!$.cookie('mr_state')) {
    //         $.cookie('mr_state', 1, {expires: 7, path: '/'});
    //     }
    //     // перезагрузка
    //     document.location.reload(true);
    // }
    //
    // // начальные установки куки для корзины товаров
    // if(!$.cookie('cart_id')) {
    //     $.cookie('cart_id', 0, {expires: 1, path: '/'});
    //     // перезагрузка
    //     document.location.reload(true);
    // }


//     $.removeCookie('mr_state');
//     $.removeCookie('opt_state');
//     $.removeCookie('purcent_state');
// $.cookie('opt_state', 1, {expires: -7, path: '/'});
// $.cookie('purcent_state', 1, {expires: -7, path: '/'});
// $.cookie('mr_state', 1, {expires: -7, path: '/'});

// console.log($.cookie());
</script>



{{-- <script type="text/javascript" src="{{ asset('assets/js/header_phones_handler.js') }}"> </script> --}}
{{--<script type="text/javascript" src="{{ asset('assets/js/main_menu_handler.js').'?v='.config('constants.version') }}"> </script>--}}
{{--<script type="text/javascript" src="{{ asset('assets/js/search_handler.js').'?v='.config('constants.version') }}"> </script>--}}
{{--<script type="text/javascript" src="{{ asset('assets/js/header_close_mobile_ico_handler.js').'?v='.config('constants.version') }}"> </script>--}}

{{--<script type="text/javascript" src="{{ asset('assets/js/lib.js').'?v='.config('constants.version') }}"> </script>--}}
{{-- <script type="text/javascript" src="{{ asset('assets/js/cart.js').'?v='.config('constants.version')  }}"> </script> --}}
{{--<script type="text/javascript" src="{{ asset('assets/js/new_cart_counts.js').'?v='.config('constants.version')  }}"> </script>--}}

<script type="text/javascript">
    // $.ajaxSetup({
    //   headers: {
    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //   }
    // });
</script>

@section('scripts')
@show

</body>
</html>
{{--{{ exit() }}--}}
