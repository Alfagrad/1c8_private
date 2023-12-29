@php
    if(session('in_home')) $style_par = "block";
        else $style_par = "none";
@endphp

<div class="open_popup js-popup-login" style="display: {{ $style_par }};">

    <div class="open_popup_wrapper">

        <div class="open_popup_info-block">

            <div class="open_popup_close-button js-login-close">×</div>

            <div class="open_popup_logo">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Alfastok logo">
            </div>

            <div class="open_popup_title">
                Вход
                <br>
                <span>в личный кабинет дилера</span>
            </div>

            <div class="open_popup_form-wrapper">

                <form class="open_popup_form" id="form-login" method="post" action="{{route('login.post')}}" >

                    {{ csrf_field() }}

                    <div class="open_popup_note-line">

                        @if (session('status'))

                            {{ session('status') }}

                        @endif

                        @if (session('error'))

                            {{ session('error') }}

                        @endif

                    </div>

                    <div class="open_popup_field-title">E-mail <span>*</span></div>
                    <input type="email" name="email" value="{{ old('email') }}" required>

                    <div class="open_popup_field-title">Пароль <span>*</span></div>
                    <input type="password" name="password" {{$password ?? ''}} required>

                    <div class="open_popup_forgot-pass">
                        <div class="js-forgot-pass">Забыли пароль?</div>
                    </div>

                    <input type="hidden" name="from_url" value="{{ session('from_url') }}">

                    <div class="open_popup_submit-block">
                        <button type="submit">Войти</button>
                        <a href="/registration">Регистрация</a>
                    </div>

                </form>

                <div class="open_popup_result-note-wrapper js-result-wrapper">

                    <div class="open_popup_result-note js-result-note">Извините.<br>Ваш аккаунт еще не активирован.</div>

                </div>

            </div>
        </div>
    </div>
</div>
