@extends('layouts.open')

@section('content')

<div class="registration-page">
    <div class="container">

        <h1>Регистрация</h1>

        <div class="registration-page_form-block">

            <form method="post" action="/registration">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="registration-page_client-type-block">

                    <div>
                        Вы:
                        <span class="form-red-star">*</span>
                    </div>

                    <div>
                        <label>
                            <input type="radio" name="user_type" value="saler" id="js-saler" required>
                            Покупатель
                        </label>                                            
                    </div>

                    <div>
                        <label>
                            <input type="radio" name="user_type" value="service" id="js-service" required>
                            Сервисный центр
                        </label>                                            
                    </div>

                </div>

                <div class="registration-page_personal-data-block">

                    <div class="registration-page_personal-data">
                        <div class="title">
                            E-mail:
                            <span class="form-red-star">*</span>
                        </div>
                        <input type="email" id="js-email" name="email" required>
                    </div>

                    <div class="registration-page_personal-data pass">
                        <div class="title">
                            Пароль:
                            <span class="form-red-star">*</span>
                        </div>
                        <input type="password" name="password" minlength="6" required>
                        <div class="eye js-pass-eye" title="Показать/скрыть символы">
                            <img src="{{ asset('assets/img/eye.png') }}">
                        </div>
                    </div>

                    <div class="registration-page_personal-data">
                        <div class="title">
                            Фамилия Имя Отчество:
                            <span class="form-red-star">*</span>
                        </div>
                        <input type="text" name="name" required>
                    </div>

                    <div class="registration-page_personal-data">
                        <div class="title">
                            Должность:
                        </div>
                        <input type="text" name="position">
                    </div>

                </div>

                <div class="registration-page_company-data-block">

                    <div class="registration-page_personal-data">
                        <div class="title">
                            Наименование юр. лица:
                            <span class="form-red-star">*</span>
                        </div>
                        <input type="text" name="company_name" required>
                    </div>

                    <div class="registration-page_personal-data">
                        <div class="title">
                            УНП (9 цифр):
                            <span class="form-red-star">*</span>
                        </div>
                        <input type="text" name="unp" pattern="[0-9]{9,9}" placeholder="9 цифр" required>
                    </div>
                    
                    <div class="registration-page_personal-data">
                        <div class="title">
                            Телефон организации:
                            <span class="form-red-star">*</span>
                        </div>
                        <input type="text" class="contact_phone" name="contact_phone" required>
                    </div>
                    
                    <div class="registration-page_personal-data">
                        <div class="title">
                            Телефон мобильный:
                        </div>
                        <input type="text" class="contact_phone" name="mobile_phone">
                    </div>

                </div>

                <div class="registration-page_captha">

                    {{-- <div class="g-recaptcha" data-sitekey="6LdVq6cUAAAAAHUc7354t9xVIOmHHDzP2vSZ5FMS"></div> --}}

                </div>

                <div class="registration-page_submit">
                    <button type="submit">Зарегистрироваться</button>
                </div>

            </form>
            
        </div>
        
    </div>
</div>

@endsection


@section('scripts')
@parent

<script type="text/javascript" src="{{ asset('assets/js/jquery.maskedinput.min.js') }}"> </script>
<script type="text/javascript">
    // маска для поля телефон
    $('.contact_phone').mask('+375 (99) 999-99-99');
</script>


<script type="text/javascript" src="{{ asset('assets/js/register.js') }}"> </script>

<script src='https://www.google.com/recaptcha/api.js'></script>

@endsection