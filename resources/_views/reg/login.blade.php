@extends('layouts.app')

@section('content')
    <body>
    <div class="b-wrapper p-login">
        <section class="s-main-wrapper">
            <div class="w-login-wrapper-table">
                <div class="w-login-wrapper-cell">
                    <form class="w-login-form"  id="form-login" method="post" action="/login" >
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <div class="w-head">
                            <img src="{{ asset('assets/img/logo.png') }}" alt="Alfastock logo">
                        </div>
                        <div class="w-name">
                            Вход
                            <br>
                            <span>в личный кабинет дилера</span>
                        </div>
                        <div class="w-body">
                            <div class="input">
                                @if (session('status'))
                                    <p>
                                        <b>{{ session('status') }}</b>
                                    </p>
                                @endif

                                @if (session('error'))
                                    <p style="color: red">
                                        {{ session('error') }}
                                    </p>
                                @endif

                            </div>
                            <div class="input">
                                <label>E-mail</label>
                                <input type="text" name="email" value="{{ old('email') }}">
                            </div>
                            <div class="input">
                                <label>Пароль</label>
                                <input type="password" name="password" {{$password or ''}}>
                                <div class="w-forgot-pass">
                                    <a href="/remember">Забыли пароль?</a>
                                </div>
                            </div>
                            <div class="input center double">
                                <input type="submit" class="button _red" value="Войти">
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
