<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlfaStok</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.ico') }}">

    @vite(['resources/scss/public.scss'])


</head>
<body>

@include('includes.headers.open')

@yield('content')

@include('includes.footer')
@include('includes.on_top_button')

@if(session('note'))
    @include('includes.popups.note')
@endif
{{--@include('includes.popups.login')--}}
@include('includes.popups.forgot_pass')

@vite(['resources/js/app.js'])
{{--@vite(['resources/js/app.js', 'resources/js/jquery.js', 'resources/js/open.js'])--}}


{{--<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>--}}

@section('scripts')
@show

</body>
</html>
{{--{{ exit() }}--}}
