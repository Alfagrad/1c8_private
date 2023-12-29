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
                            Имя пользователя:
                            <span class="form-red-star">*</span>
                        </div>
                        <input type="text" name="name" required>
                    </div>

                    <div class="registration-page_personal-data">
                        <div class="title">
                            Фамилия:
                            <span class="form-red-star">*</span>
                        </div>
                        <input type="text" name="surname" required>
                    </div>

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

                </div>

                <div class="registration-page_company-data-block">

                    <div class="registration-page_company-data">
                        <div class="title">
                            Наименование юридического лица:
                            <span class="form-red-star">*</span>
                        </div>
                        <input type="text" name="company_name" required>
                    </div>

                    <div class="registration-page_company-data">
                        <div class="title">
                            Юридический адрес:
                            <span class="form-red-star">*</span>
                        </div>
                        <input type="text" name="company_address" required>
                    </div>
                    
                </div>

                <div class="registration-page_bank-data-block">

                    <div class="registration-page_bank-data">
                        <div class="title">
                            УНП:
                            <span class="form-red-star">*</span>
                        </div>
                        <input type="text" name="unp" pattern="[0-9]{9,9}" placeholder="9 цифр" required>
                    </div>

                    <div class="registration-page_bank-data">
                        <div class="title">
                            Расчетный счет (IBAN):
                        </div>
                        <input type="text" name="bank_account" placeholder="BY52PJCB30120328871000000933">
                    </div>
                    
                    <div class="registration-page_bank-data">
                        <div class="title">
                            БИК Банка:
                        </div>
                        <input type="text" name="bank_name" placeholder="PJCBBY2X">
                    </div>

                </div>

                <div class="registration-page_contacts-data-block">

                    <div class="registration-page_contacts-block">

                        <div class="header">
                            Контактные телефоны
                        </div>

                        <div id="js-added-contacts"></div>

                        <div class="add-contact-link" id="js-add-contact-link" style="display: none">
                            Добавить контакт
                        </div>

                        <div class="add-contact-form" id="js-add-contact-form">

                            <div class="contact-block-title">
                                Добавить контакт
                            </div>

                            <div class="input-block">
                                <div class="title">
                                    Телефон:
                                    <span class="form-red-star">*</span>
                                </div>
                                <input type="text" id="contacts_phone" name="contacts_phone" required>
                            </div>

                            <div class="input-block">
                                <div class="title">
                                    Имя (должность):
                                </div>
                                <input type="text" id="contacts_name" name="contacts_name">
                            </div>

                            <div class="save-contact-button" id="js-save-contact">
                                Сохранить
                            </div>

                        </div>
                        
                    </div>

                    <div class="registration-page_contacts-block">

                        <div class="header">
                            Адреса для доставок
                        </div>

                        <div id="js-added-addresses"></div>

                        <div class="add-contact-link" id="js-add-address-link" style="display: none">
                            Добавить адрес
                        </div>

                        <div class="add-contact-form" id="js-add-address-form">

                            <div class="contact-block-title">
                                Добавить адрес
                            </div>

                            <div class="input-block">
                                <div class="title">
                                    Адрес:
                                </div>
                                <input type="text" name="addresses_address" id="addresses_address" placeholder="г. Минск, ул. Пушкина 17 офис 41">
                            </div>

                            <div class="input-block">
                                <div class="title">
                                    Дополнительный комментарий:
                                </div>
                                <input type="text" name="addresses_comment" id="addresses_comment" placeholder="Въезд со стороны ул.Ленина через шлагбаум">
                            </div>

                            <div class="save-contact-button" id="js-save-address">
                                Сохранить
                            </div>

                        </div>
                        
                    </div>

                </div>

                <div class="registration-page_shops-data-block">

                    <div class="registration-page_shops-data">
                        <div class="title">
                            Торговые объекты. Адреса. Площади:
                        </div>
                        <textarea placeholder="Ваши торговые объекты, адреса, площади. Например: Магазин электроинструмента, ул.Энгельса д3, 40м2" name="trade_object"></textarea>
                    </div>

                    <div class="registration-page_shops-data">
                        <div class="title">
                            Интернет магазины:
                        </div>
                        <textarea placeholder="Укажите ссылки на ваши интернет магазины" name="shops"></textarea>
                    </div>
                    
                    <div class="registration-page_shops-data">
                        <div class="title">
                            Регион покрытия:
                        </div>
                        <textarea placeholder="Регион покрытия. Например: Минск и частично Минский район" name="coverage_area"></textarea>
                    </div>

                </div>

                <div class="registration-page_captha">

                    <div class="g-recaptcha" data-sitekey="6LdVq6cUAAAAAHUc7354t9xVIOmHHDzP2vSZ5FMS"></div>

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

<script type="text/javascript" src="{{ asset('assets/js/register.js') }}"> </script>

<script src='https://www.google.com/recaptcha/api.js'></script>

@endsection