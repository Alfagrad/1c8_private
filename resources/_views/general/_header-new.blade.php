    <header>
        <div class="header">

            <div class="container">
                <div class="header_logo">
                    <a href="/home">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Alfastok logo" title="На главную страницу">
                    </a>
                </div>

                <div class="header_info-block">
                    <div class="">
                    <div class="header_phones-block" id="js-phones-block">

                        <div class="header_phone-ico mobile js-phones-ico">
                            @include('svg.phone_ico')
                        </div>

                        <div class="header_phones js-phones-block">

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
                                    <a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('header_vel_phone_1') );?>">
                                        {!! setting('header_vel_phone_1') !!}
                                    </a>
                                </div>
                            </div>

                            <div class="header_phone-element">
                                <div class="header_phone-line">
                                    <img src="{{ asset('assets/img/mts_ico.jpg') }}" class="header_phone-jpg-ico">
                                    <a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('header_mts_phone_1') );?>">
                                        {!! setting('header_mts_phone_1') !!}
                                    </a>
                                </div>
                            </div>

                            <div class="header_phone-element">
                                <div class="header_phone-line">
                                    <div class="header_phone-ico">
                                        @include('svg.wr_letter_ico')
                                    </div>
                                    <div class="header_write-to-us js-write-to-us">Написать письмо менеджеру</div>
                                </div>
                            </div>

                            <div class="header_phone-element">
                                <div class="header_phone-line">
                                    <div class="header_phone-ico">
                                        @include('svg.wr_director_ico')
                                    </div>
                                    <div class="header_write-to-director js-to-director">Пожаловаться директору</div>
                                </div>
                            </div>

                            <div class="header_phone-element">
                                <div class="header_phone-line">
                                    <div class="header_phone-ico">
                                        @include('svg.wr_demping_ico')
                                    </div>
                                    <div class="header_write-demping js-demping">Пожаловаться на демпинг</div>
                                </div>
                            </div>

                             @if($manager ?? false)

                                <div class="header_phone-element">
                                    <div class="header_phones_manager-name">
                                        Ваш менеджер:<br>
                                        <strong>{{ $manager->name }}</strong>
                                    </div>

                                </div>

                                @if($manager->viber)

                                    <div class="header_phone-element">
                                        <div class="header_phone-line menager">
                                            <img src="{{ asset('assets/img/viber_ico_3.png') }}" class="header_phone-ico">
                                            <a href="viber://chat?number=%2B{{ preg_replace("/\D/", "", $manager->viber) }}">
                                                {{ $manager->viber }}
                                            </a>
                                        </div>
                                    </div>

                                @endif

                                @if($manager->skype)

                                    <div class="header_phone-element">
                                        <div class="header_phone-line menager">
                                            <img src="{{ asset('assets/img/skype_ico_3.png') }}" class="header_phone-ico">
                                            <a href="skype:{{ $manager->skype }}?chat">
                                                {{ $manager->skype }}
                                            </a>
                                        </div>
                                    </div>

                                @endif

                                @if($manager->email)

                                    <div class="header_phone-element">
                                        <div class="header_phone-line menager">
                                            <img src="{{ asset('assets/img/email_ico.png') }}" class="header_phone-ico">

                                            <a href="mailto:{{ $manager->email }}">
                                                {{ $manager->email }}
                                            </a>
                                        </div>
                                    </div>

                                @endif

                            @endif

                        </div>

                    </div>

                    @php
                        if($is_service ?? false) {
                            $visibility_cl = 'no-visible';
                        } else {
                            $visibility_cl = '';
                        }
                    @endphp

                    <div class="header_info-lines header_price {{ $visibility_cl }}">
                        <div class="header_svg js-mobile-ico">
                            @include('svg.excel_ico')
                        </div>
                        <a href="{{ asset('make-prices') }}" title="Сформировать и Скачать прайсы XLS и YML ">Скачать прайс</a>
                    </div>


{{--                     <div class="header_info-lines header_currency-rate">
                        <div class="header_svg js-mobile-ico">
                            @include('svg.dollar_ico')
                        </div>
                        <div class="header_currency-rate-info">
                            <p>1 USD = {{ setting('header_usd') }} руб</p>
                            <p>1 USD для МРЦ = {{ setting('header_usd_mrc') }} руб</p>
                        </div>
                    </div> --}}

                    <div class="header_info-lines header_user-cabinet-block">
                        <div class="header_user-avatar js-mobile-ico">
                            <div class="header_svg">
                                @include('svg.user_ico')
                            </div>
                        </div>
                        <div class="header_user-cabinet-links">
                            <div class="header_user-name">
                                <a
                                    href="{{ route('profileIndex') }}"
                                    title="В кабинет - {{ \Auth::user()->profile->company_name }}"
                                >
                                    {{ \Auth::user()->profile->name }}
                                </a>
                            </div>

                            <ul class="header_user-menu">
                                <li><a href="{{ route('profileOrders') }}" title="В кабинет - История заказов">История заказов</a></li>
                                <li><a href="{{ route('profileIndex') }}" title="В кабинет - Настройки">Настройки</a></li>
                                <li><a href="{{ route('logout') }}" title="Уйти с сайта">Выйти</a></li>
                            </ul>
                        </div>
                    </div>

                    </div>

                    <div class="">

                    <div class="header_info-lines header_search-block">
                        <div class="header_search-line">
                            <input
                                type="text"
                                class="js-search"
                                name="filter_word"
                                placeholder="Поиск по каталогу"
                                onfocus="this.removeAttribute('readonly');"
                                readonly
                                autocomplete="off"
                            >
                            {{-- <input type="submit" class="js-redirect-search" data-is_service=0> --}}
                            <button type="submit" class="header_search-submit-button js-redirect-search" data-is_service=0 title="Искать">
                                <div class="header_svg">
                                    @include('svg.search_ico')
                                </div>
                            </button>
                            <input type="hidden" class="typeSearch" value="products">
                        </div>

                        <div class="search-results" style="display: none;">
                            <ul class="search-results-tabs">
                                <li class="products active">
                                    <a href="#products">
                                        Товары
                                    </a>
                                </li>
                                <li class="spares">
                                    <a href="#spares">
                                        <img src="{{ asset('assets/img/shesternia_ico.png') }}">
                                        Запчасти
                                    </a>
                                </li>
                            </ul>
                            <div class="result-content_wrapper js-search-content">
                                <div class="result-content active" id="products"></div>
                                <div class="result-content" id="spares"></div>
                            </div>
                        </div>
                    </div>

                    <div class="header_info-lines header_debt {{ $visibility_cl }}">
                        <div class="header_svg js-mobile-ico">
                            @include('svg.debt_ico')
                        </div>
                        <a href="{{ route('profileIndex') }}" title="В кабинет">
                            <span>Ваш долг:</span>
                            <span class="header_debt_my-debt" title="Ваш долг - {{ $dept_sum ?? '' }} руб">
                                {{ $dept_sum ?? ''}} руб
                            </span>
                        </a>
                    </div>

                    <div class="header_info-lines header_repare {{ $visibility_cl }}">
                        <div class="header_svg js-mobile-ico">
                            @include('svg.repare_ico')
                        </div>
                        <a href="{{ route('profileRepairs') }}" title="В кабинет - Мои ремонты" id="js-repare-block">
                            <span>Ремонты в СЦ:</span>
                            <span class="header_repare_in-work" title="{{ $repairsCountInWork ?? '' }} изделий в работе">
                                {{ $repairsCountInWork ?? '' }} в работе
                            </span>
                            <span class="header_repare_ready" title="{{$repairsCountReady ?? ''}} изделий готово">
                                {{$repairsCountReady ?? '' }} готово
                            </span>
                        </a>
                    </div>
                    </div>
                </div>
            </div>

        </div>

    </header>
