<div class="open_header">

    <div class="open_main-page_header_logo-line">
        <div class="container">
            <a href="{{ route('open.index') }}" class="open_main-page_header_logo">
                <img src="{{ asset('/assets/img/logo.png') }}">
            </a>

            <div class="open_main-page_header_info-block">

                <div class="header_phones-block" id="js-phones-block" title="Позвоните нам!">

                    <div class="header_phone-ico_mobile js-mobile-ico">
                        @include('svg.phone_ico')
                    </div>

                    <div class="header_phones">

                        <div class="header_phone-line">
                            <div class="header_phone-ico">
                                @include('svg.phone_ico')
                            </div>
                            <a href="tel:{{ '+'. preg_replace("/\D/", "", setting('header_city_phone_1')) }}">
                                {!! setting('header_city_phone_1') !!}
                            </a>
                            <div class="header_phone-arrow">
                                @include('svg.phones_arrow_ico')
                            </div>
                        </div>

                        <div class="header_phone-element">
                            <div class="header_phone-line">
                                <img src="{{ asset('assets/img/a1_ico.jpg') }}" class="header_phone-jpg-ico">
                                <a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('open_header_vel_phone') );?>">
                                    {!! setting('open_header_vel_phone') !!}
                                </a>
                            </div>
                        </div>

{{--
                         <div class="header_phone-element">
                            <div class="header_phone-line">
                                <img src="{{ asset('assets/img/mts_ico.jpg') }}" class="header_phone-jpg-ico">
                                <a href="tel:{!! '+'. preg_replace("/\D/", "", setting('header_mts_phone_1') ) !!}">
                                    {!! setting('header_mts_phone_1') !!}
                                </a>
                            </div>
                        </div>
 --}}
{{--                        <div class="header_phone-element" title="Напишите нам!">--}}
{{--                            <div class="header_phone-line">--}}
{{--                                <div class="header_phone-ico">--}}
{{--                                    @include('svg.wr_letter_ico')--}}
{{--                                </div>--}}
{{--                                <div class="header_write-to-us js-write-us">Написать письмо</div>--}}
{{--                                <div>--}}
                                    @include('includes.popups.write_us')
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                    </div>

                </div>

                <div class="open_main-page_header_address">
                    <div class="open_main-page_header_svg js-mobile-ico">
                        @include('svg.address_ico')
                    </div>
                    <address title="Адрес компании">г.Минск, ул.Основателей, 27</address>
                </div>

                <div class="open_main-page_header_brand-sites-block" id="js-sites-block">

                    <div class="header_link-ico_mobile js-mobile-ico">
                        @include('svg.brand_sites_ico')
                    </div>

                    <div x-data="{show: false}" x-on:mouseover="show=true" x-on:mouseleave="show=false" class="open_main-page_header_brand-site-links">

                        <div class="open_main-page_header_site-link-first">
                            <div class="open_main-page_header_svg">
                                @include('svg.brand_sites_ico')
                            </div>
                            <a href="http://katana.by/" target="_blank" title="Сайт бренда KATANA">
                                katana.by
                            </a>
                            <div x-on:click="show=true" class="header_phone-arrow">
                                @include('svg.phones_arrow_ico')
                            </div>
                        </div>

                        <div x-show="show" x-cloak class="open_main-page_header_site-link">
                            <a href="http://brado.by/" target="_blank" class="open_main-page_header_brand-element" title="Сайт бренда BRADO">
                                brado.by
                            </a>
                        </div>
                        <div x-show="show" x-cloak class="open_main-page_header_site-link">
                            <a href="http://skiper.by/" target="_blank" class="open_main-page_header_brand-element" title="Сайт бренда SKIPER">
                                skiper.by
                            </a>
                        </div>
                        <div x-show="show" x-cloak class="open_main-page_header_site-link">
                            <a href="http://spec-e.by/" target="_blank" class="open_main-page_header_brand-element" title="Сайт бренда SPEC">
                                spec-e.by
                            </a>
                        </div>

                    </div>

                </div>

{{--                 <div class="open_main-page_header_languiges-block" id="js-lang-block">

                    <div class="header_lang-ico_mobile js-mobile-ico">
                        @include('svg.ru_ico')
                    </div>

                    <div class="open_main-page_header_languiges">
                        <div>
                            <a href="#" class="first" title="Русский">
                                RU
                                <div class="header_phone-arrow">
                                    @include('svg.phones_arrow_ico')
                                </div>
                            </a>
                        </div>
                        <div class="open_main-page_header_languige-element">
                            <a href="#" title="English">
                                EN
                            </a>
                        </div>
                    </div>
                </div> --}}

{{--                <a href="{{ route('home.index') }}" class="open_main-page_header_dealer-enter js-dealer-enter" title="Вход / Регистрация дилера">--}}
{{--                    <div class="open_main-page_header_svg">--}}
{{--                        @include('svg.enter_ico')--}}
{{--                    </div>--}}
{{--                    <div class="open_main-page_header_dealer-text">Вход / Регистрация</div>--}}
{{--                </a>--}}

                @include('includes.popups.login')

            </div>
        </div>
    </div>


    <div class="open_header_menu-line">
        <div class="container">

            <div class="open_header_main-menu-block">

                <div class="open_header_main-menu-mobile">

                    <div class="open_main-menu_burger-block" id="js-burger">

                        <div class="open_main-menu_lines" id="js-lines">
                            <div class="hr-1"></div>
                            <div class="hr-2"></div>
                            <div class="hr-3"></div>
                        </div>

                        <div class="open_main-menu_text">
                            Меню
                        </div>

                    </div>

                </div>

                <nav class="open_header_main-menu" id="js-main-menu">

                    <ul >
                        <li>
                            <a
                                href="{{ route('open.index') }}"
                                @if(Route::currentRouteName() == 'open') class="active" @endif
                            >
                                Главная
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('open.dealer') }}"
                               @if(Route::currentRouteName() == 'open.dealer') class="active" @endif
                            >
                                Дилерам
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('open.brand') }}"
                               @if(Route::currentRouteName() == 'open.brand') class="active" @endif
                            >
                                Бренды
                            </a>
                        </li>
                        <li class="service-menu">
                            <a href="#"
                                class="js-service-menu
                                @if(Route::currentRouteName() == 'openServicePage' || Route::currentRouteName() == 'openServiceDocs' || Route::currentRouteName() == 'openEripPay' || Route::currentRouteName() == 'openRepairStatus'){{ "active"}}@endif"
                            >
                                Сервис
                                <span class="arrow">
                                    @include('svg.phones_arrow_ico')
                                </span>
                            </a>

                            <ul class="service-sub-menu">
                                <li>
                                    <a href="{{ route('open.service') }}" class="@if(Route::currentRouteName() == 'open.service'){{ "active"}}@endif">
                                        Адреса Сервисных Центров
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('open.service.documents') }}" class="@if(Route::currentRouteName() == 'open.service.documents'){{ "active"}}@endif">
                                        Ремонты, брак, документы СЦ
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('open.erip') }}" class="@if(Route::currentRouteName() == 'open.erip'){{ "active"}}@endif">
                                        Оплата через ЕРИП
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('repair.status') }}" class="@if(Route::currentRouteName() == 'repair.status'){{ "active"}}@endif">
                                        Проверить статус ремонта
                                    </a>
                                </li>
                            </ul>

                        </li>
                        <li>
                            <a
                                href="{{ route('open.vacancy') }}"
                                @if(Route::currentRouteName() == 'open.vacancy') class="active" @endif
                            >
                                Вакансии
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('open.contact') }}"
                               @if(Route::currentRouteName() == 'open.contact') class="active" @endif
                            >
                                Контакты
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>

{{--            <div class="open_main-page_header_shop-links">--}}
{{--                <a href="http://www.alfagradm.by/" class="open_main-page_header_alfagradm-link" target="_blank" title="В интернет магазин alfagradm.by">--}}
{{--                    <img src="{{ asset('assets/img/alfagradm_logo.png') }}" alt="alfagradm logo">--}}
{{--                </a>--}}
{{--                <a href="http://7150.by/" class="open_main-page_header_7150-link" target="_blank" title="В интернет магазин 7150.by">--}}
{{--                    <img src="{{ asset('assets/img/logo7150.png') }}" alt="7150 logo">--}}
{{--                </a>--}}
{{--            </div>--}}

        </div>
    </div>

</div>

