@extends('layouts.app')

@section('content')
    <body>
    <div class="b-wrapper p-login">
        <section class="s-main-wrapper">
            <div class="w-login-wrapper-table">
                <div class="w-login-wrapper-cell">
                    <form class="w-login-form" id="form-remember-password" method="post" action="/remember/restore">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <div class="w-head">
                            <a href="/">
                                <img src="{{ asset('assets/img/logo.png') }}" alt="Alfastock logo">
                            </a>
                        </div>
                        <div class="w-name">Напомнить пароль</div>
                        <div class="w-body">
                            <div class="input">
                                <label>E-mail</label>
                                <input type="text" name="email">
                            </div>
                            <div class="input center double">
                                <input type="submit" class="button _red js-btn-forgotpass" value="Напомнить">
                                <a href="/registration" class="button _gray">Регистрация</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="empty-footer"></div>
            @include('reg.partials.footer')
        </section>
        @include('reg.partials.popup')
    </div>
    @include('general.scripts')
    </body>
@endsection
