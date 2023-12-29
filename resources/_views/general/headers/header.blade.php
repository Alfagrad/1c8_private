<header>
    <div class="header">

        <div class="container flex space-x-2 items-center">
            <div class="header_logo">
                <a href="/home">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Alfastok logo" title="На главную страницу">
                </a>
            </div>

            <div class="header_info-block">
                <div class="">
                    <div class="header_phones-block" id=_"js-phones-block">

                        <div class="header_phone-ico mobile _js-phones-ico">
                            @include('svg.phone_ico')
                        </div>

                        <div class="header_phones _js-phones-block">

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

                    @yield('download-price')


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

                    @yield('debt')


                </div>
            </div>




            <div class="header_info-lines header_user-cabinet-block flex flex-col space-y-2 w-1/4">
                <div class="">
                    <div class="header_user-avatar js-mobile-ico flex space-x-2 items-center">
                        <div class="header_svg">
                            @include('svg.user_ico')
                        </div>
                        <div class="header_user-name">
                            <a
                                href="{{ route('profileIndex') }}"
                                title="В кабинет - {{ \Auth::user()->profile->company_name }}"
                            >
                                {{ \Auth::user()->profile->name }}
                            </a>
                        </div>
                    </div>
                    <div class="header_user-cabinet-links">
                        <ul class="header_user-menu">
                            @yield('order-history')
                            <li><a href="{{ route('profileIndex') }}" title="В кабинет - Настройки">Настройки</a></li>
                            <li><a href="{{ route('logout') }}" title="Уйти с сайта">Выйти</a></li>
                        </ul>
                    </div>
                </div>
                @yield('repairs')
                @yield('service-toggle')
            </div>

        </div>





    </div>

</header>
