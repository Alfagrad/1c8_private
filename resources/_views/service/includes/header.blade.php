<!--HEADER-->
<header class="">
    <div class="container">
        <div class="wrapper">
            <div class="left">
                <div class="h-logo service-logo">
                    <div class="w-logo">
                        <div class="logo">
                            <a href="/service">
                                <img src="{{ asset('assets/img/logo.png') }}" alt="Alfastock logo">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right service-right">
                <div class="h-customer">
                    <div class="wrapper w-customer-info">
                        <div class="name">
                            <a href="{{route('profileIndex')}}">{{$generalProfile->company_name}}</a>
                        </div>

                        <ul class="customer-config-menu">
                            <li><a href="{{route('profileIndex')}}">Кабинет</a></li>
                            <li><a href="{{route('logout')}}">Выйти</a></li>
                        </ul>

{{--                         <div class="w-debt">
                            <a href="{{route('profileIndex')}}">
                                Мой долг:
                                <span class="_hidden-info">{{number_format($deptSum, 2, ',', ' ')}} руб
                                    <div class="cursor-hover-info">Ваш долг</div>
                                </span>
                            </a>
                        </div>

                        <div class="w-repairs">
                            <a href="{{route('profileRepairs')}}">Ремонты в СЦ:
                                <span class="_hidden-info">{{$repairsCountInWork}} в работе
                                    <div class="cursor-hover-info">Ремонты в работе</div>
                                </span>
                                <span class="ready _hidden-info">{{$repairsCountReady}} готово
                                    <div class="cursor-hover-info">Ремонты готовы</div>
                                </span>
                            </a>
                        </div>
 --}}
                    </div>
                </div>
            </div>
            <div class="middle service-middle">
                <div class="h-contacts service-phones">
                    <div class="w-contacts">
                        <div class="w-dropper">
                            <div class="w-dropper-hovered">
                                <div class="phones">
                                    <div class="tel _vel"><a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('header_service_phone_1') );?>">{!! setting('header_service_phone_1') !!}</a></div>
                                    <div class="tel _vel"><a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('header_service_phone_2') );?>">{!! setting('header_service_phone_2') !!}</a></div>
                                </div>
                                <div class="_notebook-show">
                                    <div class="button _write js-b-write-to-us"><a href="">Написать письмо</a></div>
                                    <div class="button _gray js-b-to-director"><a href="">Пожаловаться директору</a></div>
{{--                                     <div class="button _red js-b-demping"><a href="">Пожаловаться на демпинг</a></div> --}}
                                </div>
{{--                                 @if($generalProfile->manager_name)
                                    <div class="phones">
                                        <div class="personal-manager-name">Ваш менеджер {{$generalProfile->manager_name}}</div>
                                        @if($generalProfile->manager_viber)
                                            <div class="tel _viber"><a href="viber://add?number={{str_replace('+', '', $generalProfile->manager_viber)}}">{{$generalProfile->manager_viber}}</a></div>
                                        @endif
                                        @if($generalProfile->manager_skype)
                                            <div class="tel _skype"><a href="skype:{{$generalProfile->manager_skype}}?chat"><b>{{$generalProfile->manager_skype}}</b></a></div>
                                        @endif
                                    </div>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input">
                    <div class="h-search">
                        <div class="search">
                            <input type="text" class="js-search" name="filter_word" placeholder="Поиск по каталогу" onfocus="this.removeAttribute('readonly');" readonly autocomplete="off">
                            <input type="submit" class="js-redirect-search" data-is_service=1>
                            <input type="hidden" class="typeSearch" value="products">
                        </div>
                    </div>
                    <div class="w-hidden-search-results" style="display: none">
						<ul class="search-results-tabs">
							<li class="active products">
								<a href="#products">Товары</a>
							</li>
							<li class="spares">
								<a href="#spares"><svg role="img" aria-hidden="true" width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><path d="M15.603 6.512l-1.428-.172c-.146-.545-.36-1.059-.635-1.537l.888-1.128c.136-.173.12-.442-.037-.598l-1.471-1.472c-.155-.156-.424-.172-.598-.036l-1.13.889c-.476-.275-.99-.489-1.533-.634l-.171-1.428c-.026-.218-.228-.397-.448-.397h-2.081c-.22 0-.422.179-.447.397l-.172 1.428c-.544.145-1.059.36-1.535.634l-1.13-.888c-.173-.136-.442-.12-.597.036l-1.472 1.473c-.155.155-.171.424-.035.598l.887 1.129c-.275.476-.489.991-.634 1.536l-1.427.171c-.218.026-.397.227-.397.448v2.081c0 .22.179.42.397.447l1.428.172c.146.543.359 1.057.634 1.533l-.887 1.13c-.135.174-.12.442.036.598l1.469 1.473c.156.155.424.172.597.037l1.13-.889c.476.275.991.489 1.535.634l.172 1.427c.026.219.227.397.447.397h2.081c.221 0 .422-.178.447-.397l.172-1.427c.545-.146 1.059-.36 1.535-.634l1.13.888c.173.136.442.12.597-.036l1.471-1.473c.156-.155.172-.424.036-.598l-.888-1.129c.276-.476.489-.991.635-1.534l1.427-.172c.219-.026.397-.226.397-.447v-2.081c.001-.221-.177-.422-.397-.448zm-7.603 5.488c-2.209 0-4-1.791-4-4s1.791-4 4-4 4 1.791 4 4-1.79 4-4 4z"></path></svg>Запчасти</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade in active" id="products"></div>
							<div class="tab-pane fade" id="spares"></div>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!--HEADER END-->
