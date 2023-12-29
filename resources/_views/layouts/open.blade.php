<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlfaStok</title>

    {{-- version --}}
    {{-- @php($version = date('Ymd'))  --}}
    @php($version = 'fgfj74')

    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.ico') }}">

    @vite(['resources/scss/public.scss'])


</head>
<body>

@include('general.open_header')

@yield('content')

@include('general.open-footer')
@include('general.open_on_top_button')
@include('general.open_popup')
@if(session('note'))
    @include('general.open_popup_note')
@endif
@include('reg.open_popup_login')
@include('reg.open_popup_forgot_pass')

@vite(['resources/js/app.js', 'resources/js/jquery.js', 'resources/js/open.js'])


{{--<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>--}}

@section('scripts')
@show

</body>
</html>
{{--{{ exit() }}--}}
