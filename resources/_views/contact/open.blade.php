@extends('layouts.open')

@section('content')

    <div class="open_contact-page">

        <div class="url-line">

            <div class="container">

                <a href="{{ asset('/') }}">Главная</a> - <span>Контакты</span>

            </div>

        </div>

        <div class="open_contact-page_contacts">

            <div class="container">

                <h2>Наши контакты</h2>

                <h2>Отдел оптовых продаж</h2>

                <div class="open_contact-page_contacts-block">

                    <div class="open_contact-page_contact-info">

                        <h3><span class="red-round"></span>Адрес:</h3>
                        <p>
                            Минский район, Московское шоссе, 9-й км., 4, корп. 1, каб. 101
                        </p>

                        <p>
                            Яндекс.карта:
                            <a href="https://yandex.by/maps/-/CDa0E6jV" class="link">
                                53.964593, 27.745191
                            </a>
                            <br>
                            Гугл карта:
                            <a href="https://maps.app.goo.gl/ho7iujprtH6VLKx77" class="link">
                                53.964481, 27.745045
                            </a>
                        </p>

                        <p>
                            Заезд со стороны Московского шоссе, под шлагбаум, за остановкой общественного транспорта "9-й километр".
                            Здание отдела продаж находится за магазином "Санта".
                        </p>

                        <h3><span class="red-round"></span>Режим работы:</h3>
                        <p>
                            Пн-Пт: с 9-00 до 17-30<br>
                            Сб-Вс: выходной<br>
                        </p>

                        <h3><span class="red-round"></span>Телефоны:</h3>
                        <p>
                            <a href="tel:+375291262626">+375 (29) 126-26-26</a><br>
                            <a href="tel:+375295638228">+375 (29) 563-82-28</a><br>
                            <a href="tel:+375 297884896">+375 (29) 788-48-96</a>
                        </p>

                    </div>

                    <div class="open_contact-page_contact-map">
                        <div style="position:relative;overflow:hidden;"><a href="https://yandex.by/maps/org/vitalyur/156505181884/?utm_medium=mapframe&utm_source=maps" style="color:#eee;font-size:12px;position:absolute;top:0px;">Виталюр</a><a href="https://yandex.by/maps/29630/minsk-district/category/sports_center/184107313/?utm_medium=mapframe&utm_source=maps" style="color:#eee;font-size:12px;position:absolute;top:14px;">Спортивный комплекс в Минской области</a><iframe src="https://yandex.by/map-widget/v1/?ll=27.747370%2C53.963804&mode=poi&poi%5Bpoint%5D=27.745191%2C53.964593&poi%5Buri%5D=ymapsbm1%3A%2F%2Forg%3Foid%3D156505181884&utm_source=share&z=17" width="100%" height="290px" frameborder="1" allowfullscreen="true" style="position:relative;"></iframe></div>
                        {{--                        <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d2347.1569254412493!2d27.745045!3d53.964481!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zNTPCsDU3JzUyLjEiTiAyN8KwNDQnNDIuMiJF!5e0!3m2!1sru!2sby!4v1698137268780!5m2!1sru!2sby" width="100%" height="100%"  style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>--}}
                    </div>

                </div>

            </div>

        </div>

        <br>
        <br>

        <div class="open_contact-page_contacts">

            <div class="container">

                <h2>Отдел розничных продаж, офис, склад, сервисный центр (г.Минск)</h2>

                <div class="open_contact-page_contacts-block">

                    <div class="open_contact-page_contact-info">

                        <h3><span class="red-round"></span>Адрес:</h3>
                        <p>
                            Республика Беларусь, г.Минск, ул. Рогачевская, 14/14<br>
                            <a href="{{ asset('/storage/route_schema_2022_2.pdf') }}" class="link">
                                Скачать схему проезда
                            </a>
                        </p>

                        <p>
                            Координаты для навигатора:<br>
                            <a href="https://www.google.by/maps/place/ООО+Альфасток/@53.9444444,27.7311446,674m/data=!3m1!1e3!4m13!1m7!3m6!1s0x0:0x0!2zNTPCsDU2JzQwLjAiTiAyN8KwNDQnMDAuMCJF!3b1!8m2!3d53.9444444!4d27.7333333!3m4!1s0x0:0x70af653367064500!8m2!3d53.944452!4d27.7332526" class="link">
                                53°56′40″N, 27°44′E
                            </a>
                            или
                            <a href="https://www.google.by/maps/place/ООО+Альфасток/@53.9444444,27.7311446,674m/data=!3m1!1e3!4m13!1m7!3m6!1s0x0:0x0!2zNTPCsDU2JzQwLjAiTiAyN8KwNDQnMDAuMCJF!3b1!8m2!3d53.9444444!4d27.7333333!3m4!1s0x0:0x70af653367064500!8m2!3d53.944452!4d27.7332526" class="link">
                                53.944446, 27.733403
                            </a>
                        </p>

                        <h3><span class="red-round"></span>Режим работы:</h3>
                        <p>
                            Пн-Пт: с 9-00 до 17-30<br>
                            Сб-Вс: выходной
                        </p>

                        <h3><span class="red-round"></span>Телефоны отдела розничных продаж:</h3>
                        <p>
                            <a href="tel:+375291252626">+375 (29) 125-26-26</a><br>
                            <a href="tel:+375298762071">+375 (29) 876-20-71</a><br>
                        </p>

                        <h3><span class="red-round"></span>E-mail:</h3>
                        <p>
                            <a href="mailto:1252626@alfagrad.com">1252626@alfagrad.com</a>
                        </p>

                        <h3><span class="red-round"></span>Телефоны:</h3>
                        <p>
                            Тел: <a href="tel:+375173886288">+375 (17) 388-62-88</a><br>
                            A1: <a href="tel:+375291226677">+375 (29) 122-66-77</a>
                            <img src="{{ asset('assets/img/sety.png') }}">
                            <br>
                            Факс: +375 (17) 388-61-88
                        </p>

                        <h3><span class="red-round"></span>E-mail:</h3>
                        <p>
                            <a href="mailto:alfagrad@alfagrad.com">alfagrad@alfagrad.com</a>
                        </p>

                    </div>

                    <div class="open_contact-page_contact-map">
                        <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A8-bZi6ddmti9v5LpgeoxwoOj456qIdLH&amp;source=constructor" width="100%" height="100%" frameborder="0"></iframe>
                    </div>

                </div>


            </div>

        </div>

        <div class="open_contact-page_contacts-2">

            <div class="container">

                <div class="block">
                    {{--                <div class="open_contact-page_contact-opt">--}}

                    {{--                <h2>Отдел оптовых продаж</h2>--}}

                    {{--                <h3><span class="red-round"></span>Телефоны:</h3>--}}
                    {{--                <p>--}}
                    {{--                    <a href="tel:+375291262626">+375 (29) 126-26-26</a><br>--}}
                    {{--                    <a href="tel:+375295638228">+375 (29) 563-82-28</a><br>--}}
                    {{--                    <a href="tel:+375 297884896">+375 (29) 788-48-96</a>--}}
                    {{--                </p>--}}

                    {{--                </div>--}}

                    <div class="open_contact-page_contact-claim">

                        <h2>Отдел по работе с рекламациями</h2>

                        <h3><span class="red-round"></span>Телефоны:</h3>
                        <p>
                            <a href="tel:+375333251154">+375 (33) 325-11-54</a>
                            <img src="{{ asset('assets/img/sety.png') }}">
                            <br>
                            <a href="skype:skype.sklad2@alfastok.by">skype.sklad2@alfastok.by</a>
                            <img src="{{ asset('assets/img/skype2_ico.png') }}">
                        </p>

                    </div>
                </div>

                <div class="block">
                    <div class="open_contact-page_requisites">

                        <h2>Реквизиты</h2>

                        <div>
                            <strong>Общество с ограниченной ответственностью «Альфасад»<br>
                                (ООО «Альфасад»)</strong>
                        </div>

                        <div>
                            <p>
                                <span class="red-round"></span>BY 48 PJCB 3012 0544 6810 0000 0933<br>
                                <span class="red-round"></span>в ОАО «Приорбанк» г. Минск<br>
                                <span class="red-round"></span>код банка PJCBBY2X (текущий, белорусские рубли)
                            </p>
                        </div>

                        <div>
                            <p>
                                <span class="red-round"></span>ОКПО 501196345000<br>
                                <span class="red-round"></span>GLN 4812561900004<br>
                                <span class="red-round"></span>УНП 192987077
                            </p>
                        </div>

                        <div>
                            <strong>Наименование EDI-провайдера:</strong><br>
                            ООО «Современные технологии торговли»
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection

@section('scripts')
@parent

@vite(['resources/js/service_toggler.js'])

{{--<script type="text/javascript" src="{{ asset('assets/js/service_toggler.js') }}"></script>--}}

@endsection
