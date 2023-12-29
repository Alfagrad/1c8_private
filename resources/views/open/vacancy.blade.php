@extends('layouts.open')

@section('content')

<div class="open_vacancy-page">

    <div class="url-line">

        <div class="container">

            <a href="{{ asset('/') }}">Главная</a> - <span>Вакансии</span>

        </div>

    </div>

    <div class="open_vacancy-page_advantages">

        <div class="container">

            <h2 class="open_vacancy-page_title">Преимущества построения карьеры в нашей компании</h2>

            <div class="open_vacancy-page_advantages-block">

                <div class="open_vacancy-page_advantage-element">

                    <div class="open_vacancy-page_advantage_img-block">
                        <div class="open_vacancy-page_advantage_img-background">
                            <div class="open_vacancy-page_advantage_svg">
                                @include('svg.peoples_ico')
                            </div>
                        </div>
                    </div>

                    <div class="open_vacancy-page_advantage_description">
                        Дружелюбная открытая атмосфера
                    </div>

                </div>

                <div class="open_vacancy-page_advantage-element">

                    <div class="open_vacancy-page_advantage_img-block">
                        <div class="open_vacancy-page_advantage_img-background">
                            <div class="open_vacancy-page_advantage_svg">
                                @include('svg.hands_ico')
                            </div>
                        </div>
                    </div>

                    <div class="open_vacancy-page_advantage_description">
                        Стабильная заработная плата
                    </div>

                </div>

                <div class="open_vacancy-page_advantage-element">

                    <div class="open_vacancy-page_advantage_img-block">
                        <div class="open_vacancy-page_advantage_img-background">
                            <div class="open_vacancy-page_advantage_svg">
                                @include('svg.document_ico')
                            </div>
                        </div>
                    </div>

                    <div class="open_vacancy-page_advantage_description">
                        Официальное трудоустройство
                    </div>

                </div>

                <div class="open_vacancy-page_advantage-element">

                    <div class="open_vacancy-page_advantage_img-block">
                        <div class="open_vacancy-page_advantage_img-background">
                            <div class="open_vacancy-page_advantage_svg">
                                @include('svg.awards_ico')
                            </div>
                        </div>
                    </div>

                    <div class="open_vacancy-page_advantage_description">
                        Корпоративные мероприятия
                    </div>

                </div>

                <div class="open_vacancy-page_advantage-element">

                    <div class="open_vacancy-page_advantage_img-block">
                        <div class="open_vacancy-page_advantage_img-background">
                            <div class="open_vacancy-page_advantage_svg">
                                @include('svg.bus_ico')
                            </div>
                        </div>
                    </div>

                    <div class="open_vacancy-page_advantage_description">
                        Доставка от/до ст.м. Уручье
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="open_vacancy-page_company-part">

        <div class="container">

            <h2 class="open_vacancy-page_company-part_title">Станьте частью нашей команды!</h2>

            <div class="open_vacancy-page_company-part-block">

                <div class="open_vacancy-page_company-part_info">

                    <p>Компания Альфасад - это современная компания, которая заботится не только о росте собственного благосостояния, но и своих сотрудников!</p>
                    <p>Мы предлагаем:</p>
                    <p class="marker">
                        <span class="red-round"></span>Понимающее руководство<br>
                        <span class="red-round"></span>Задачи, которые позволяют реализовывать профессиональный потенциал<br>
                        <span class="red-round"></span>Признание успехов наших сотрудников<br>
                        <span class="red-round"></span>Комфортные рабочие места и место для питания<br>
                        <span class="red-round"></span>Стабильная зарплата, всегда вовремя на банковскую карточку 2 раза в месяц (оклад + KPI + %)<br>
                        <span class="red-round"></span>Удобный график работы 5/2, 9:00-17:30<br>
                        <span class="red-round"></span>Доставка транспортом нанимателя от-до ст. м. Уручье, адрес офиса: г. Минск, ул. Рогачевская, 14/14<br>
                        <span class="red-round"></span>Официальное трудоустройство и социальный пакет<br>
                        <span class="red-round"></span>Возможность личного и карьерного роста<br>
                        <span class="red-round"></span>Корпоративные мероприятия<br>
                        <span class="red-round"></span>Продажа товара собственных брендов
                    </p>
                    <p>Будем рады видеть Вас нашим коллегой!</p>

                </div>

                <div class="open_vacancy-page_company-part_youtube">
                    <iframe src="https://www.youtube.com/embed/74o-WE8bRh4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>

            </div>

        </div>

    </div>

    <div class="open_vacancy-page_vacancies">

        <div class="container">

            <h2 class="open_vacancy-page_vacancies_title">Наши вакансии</h2>

            <div class="open_vacancy-page_vacancies-block">

                @foreach($vacancies as $vacancy)

                <div x-data="{show:false}" class="open_vacancy-page_vacancy-element">

                    <div class="open_vacancy-page_vacancy-element_title-line">

                        <h3>{{ $vacancy->name }}</h3>

                        <div class="open_vacancy-page_vacancy-element_salary">
                            {{ $vacancy->salary }}

                            <div @click="show=!show" class="open_vacancy-page_vacancy-element_arrow">
                                @include('svg.phones_arrow_ico')
                            </div>

                        </div>


                    </div>

                    <div x-show="show" x-cloak class="open_vacancy-page_vacancy-element_text-block">

                        <div class="open_vacancy-page_vacancy-element_text">
                            {!! $vacancy->text !!}
                        </div>

                        <div class="open_vacancy-page_vacancy-element_vacancy-link">
                            <a href="{{ $vacancy->link }}" target="_blank" title="Переход к вакансии на Rabota.by">Откликнуться на вакансию</a>
                        </div>

                        <div class="open_vacancy-page_vacancy-element_vacancy-note">
                            <span>*</span> отклик на вакансию на сайте rabota.by
                        </div>

                    </div>


                </div>

                @endforeach

            </div>

        </div>

    </div>



</div>

@endsection



@section('scripts')
@parent

<script type="text/javascript" src="{{ asset('assets/js/vacancy_handler.js') }}"></script>


@endsection
