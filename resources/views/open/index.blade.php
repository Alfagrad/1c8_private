@extends('layouts.open')

@section('content')

<div class="open_main-page_banner-block">

{{--     <video autoplay="" loop="" muted="" poster="{{ asset('/assets/img/video_first_pic.jpg') }}">
        <source src="{{ asset('assets/video/main_page_video.mp4') }}" type="video/mp4">
    </video>
 --}}

    {{-- <img src="{{ asset('/assets/img/open_main_page_banner.jpg') }}"> --}}

{{--     <div class="open_main-page_banner-block_text-wrapper">
        <div class="container">
            <div class="open_main-page_banner-text">
                Наша компания - первый импортер в Беларусь садовой техники, строительного оборудования и инструмента торговых марок KATANA, ALTRON, WELT, SKIPER, BRADO, DARC, SBK, SPEC
            </div>
        </div>
    </div>
 --}}

    <div class="container">

        <div class="main-page_promo-line_slider-container">

            <div class="main-page_promo-line_slider-block" data-pic-count="4">

                <div class="main-page_promo-line_slider-element js-img-link" data-pic="1">

                    <img src="{{ asset('assets/img/slide_1.jpg') }}">

                    <div class="main-page_promo-line_slider-description-wrapper">
                        <div class="main-page_promo-line_slider-description">
                            ЛЕГКО И ВЫГОДНО
                        </div>
                    </div>

                </div>

                <div class="main-page_promo-line_slider-element js-img-link" data-pic="2">

                    <img src="{{ asset('assets/img/slide_2.jpg') }}">

                    <div class="main-page_promo-line_slider-description-wrapper">
                        <div class="main-page_promo-line_slider-description">
                            БЫСТРО И ТОЧНО
                        </div>
                    </div>

                </div>

                <div class="main-page_promo-line_slider-element js-img-link" data-pic="3">

                    <img src="{{ asset('assets/img/slide_3.jpg') }}">

                    <div class="main-page_promo-line_slider-description-wrapper">
                        <div class="main-page_promo-line_slider-description">
                            РЕГУЛЯРНО И ВОВРЕМЯ
                        </div>
                    </div>

                </div>

                <div class="main-page_promo-line_slider-element js-img-link" data-pic="4">

                    <img src="{{ asset('assets/img/slide_4.jpg') }}">

                    <div class="main-page_promo-line_slider-description-wrapper">
                        <div class="main-page_promo-line_slider-description">
                            НАДЕЖНО И КАЧЕСТВЕННО
                        </div>
                    </div>

                </div>

            </div>

            <div class="main-page_slider-lines-block">

                @for($i = 1; $i <= 4; $i++)

                <div
                    class="main-page_slider-lines-block_line"
                    data-line="{{ $i }}"
                >
                    <div class="main-page_slider-lines-block_line-back"></div>
                </div>

                @endfor

            </div>

        </div>

    </div>

</div>

<div class="open_main-page_about-block">
    <div class="container">
        Наша компания - первый импортер в Беларусь садовой техники, строительного оборудования, хозяйственного инвентаря и инструмента торговых марок KATANA, ALTRON, WELT, SKIPER, BRADO, DARC, SBK, SPEC
    </div>
</div>

<div class="open_main-page_block-1">
    <div class="container">
        <div class="open_main-page_block-1-element">
            <div class="open_main-page_block-1-svg">
                @include('svg.block_1_svg_1')
            </div>
            <div class="open_main-page_block-1-rating">
                >20
            </div>
            <div class="open_main-page_block-1-description">
                лет успешно работаем на рынке
            </div>
        </div>
        <div class="open_main-page_block-1-element">
            <div class="open_main-page_block-1-svg">
                @include('svg.block_1_svg_2')
            </div>
            <div class="open_main-page_block-1-rating">
                >1500
            </div>
            <div class="open_main-page_block-1-description">
                дилеров по всей Беларуси
            </div>
        </div>
        <div class="open_main-page_block-1-element">
            <div class="open_main-page_block-1-svg">
                @include('svg.block_1_svg_3')
            </div>
            <div class="open_main-page_block-1-rating">
                >115
            </div>
            <div class="open_main-page_block-1-description">
                поставщиков из Китая, России и Европы
            </div>
        </div>
        <div class="open_main-page_block-1-element">
            <div class="open_main-page_block-1-svg">
                @include('svg.block_1_svg_4')
            </div>
            <div class="open_main-page_block-1-rating">
                >20000
            </div>
            <div class="open_main-page_block-1-description">
                проектный ассортимент
            </div>
        </div>
        <div class="open_main-page_block-1-element">
            <div class="open_main-page_block-1-svg">
                @include('svg.block_1_svg_5')
            </div>
            <div class="open_main-page_block-1-rating">
                >10000
            </div>
            <div class="open_main-page_block-1-description">
                метров<span>2</span> офисно-складской комплекс
            </div>
        </div>
        <div class="open_main-page_block-1-element">
            <div class="open_main-page_block-1-svg">
                @include('svg.block_1_svg_6')
            </div>
            <div class="open_main-page_block-1-rating">
                >100
            </div>
            <div class="open_main-page_block-1-description">
                сотрудников
            </div>
        </div>

    </div>
</div>

<div class="open_main-page_block-2">
    <div class="container">
        <div class="open_main-page_block-2_text-block">
            <div class="open_main-page_block-2_text-block-element">
                <div class="open_main-page_block-2_text-header">
                    >150 населенных пунктов
                </div>
                <div class="open_main-page_block-2_description">
                    насчитывает наша широкая дилерская сеть
                </div>
            </div>
            <div class="open_main-page_block-2_text-block-element">
                <div class="open_main-page_block-2_text-header">
                    >30 сервисных центров
                </div>
                <div class="open_main-page_block-2_description">
                    СЦ в каждом областном городе
                </div>
            </div>
            <div class="open_main-page_block-2_text-block-element">
                <div class="open_main-page_block-2_text-header">
                    Собственный автопарк
                </div>
                <div class="open_main-page_block-2_description">
                    легковых и грузовых автомобилей
                </div>
            </div>
        </div>
        <div class="open_main-page_block-2_map-block">
            <img src="{{ asset('/assets/img/karta_so_strelkami.png') }}" alt="karta so strelkami">
        </div>
    </div>
</div>

<div class="open_main-page_block-3-stages">

    <div class="container">

        <div class="open_main-page_block-3-stages_title">
            Этапы развития компании
        </div>

        <div class="open_main-page_stage-element">
            <div class="open_main-page_stage-element_img-block">
                <div class="open_main-page_stage-element_round">
                    <div class="open_main-page_stage-element_round-in">
                        1
                    </div>
                </div>
                <div class="open_main-page_stage-element_line">
                </div>
                <div class="open_main-page_stage-element_arrow left">
                    @include('svg.block_2_arrow')
                    <div class="open_main-page_stage-element_white-round-wrapper">
                        <div class="open_main-page_stage-element_white-round">
                        </div>
                    </div>
                </div>
            </div>
            <div class="open_main-page_stage-element_text-block">
                <div class="open_main-page_stage-element_year">
                    2003 год
                </div>
                <div class="open_main-page_stage-element_header">
                    ОСНОВАНИЕ КОМПАНИИ
                </div>
                <div class="open_main-page_stage-element_description">
                    В 2003 году компания начала свою деятельность
                </div>
            </div>
        </div>

        <div class="open_main-page_stage-element">
            <div class="open_main-page_stage-element_text-block">
                <div class="open_main-page_stage-element_year">
                    2005 год
                </div>
                <div class="open_main-page_stage-element_header">
                    ОРИЕНТАЦИЯ НА КЛИЕНТА
                </div>
                <div class="open_main-page_stage-element_description">
                    в 2005 году наша дилерская сеть насчитывала более 500 дилеров по всей стране
                </div>
            </div>
            <div class="open_main-page_stage-element_img-block">
                <div class="open_main-page_stage-element_round">
                    <div class="open_main-page_stage-element_round-in">
                        2
                    </div>
                </div>
                <div class="open_main-page_stage-element_line">
                </div>
                <div class="open_main-page_stage-element_arrow right">
                    @include('svg.block_2_arrow')
                    <div class="open_main-page_stage-element_white-round-wrapper">
                        <div class="open_main-page_stage-element_white-round">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="open_main-page_stage-element">
            <div class="open_main-page_stage-element_img-block">
                <div class="open_main-page_stage-element_round">
                    <div class="open_main-page_stage-element_round-in">
                        3
                    </div>
                </div>
                <div class="open_main-page_stage-element_line">
                </div>
                <div class="open_main-page_stage-element_arrow left">
                    @include('svg.block_2_arrow')
                    <div class="open_main-page_stage-element_white-round-wrapper">
                        <div class="open_main-page_stage-element_white-round">
                        </div>
                    </div>
                </div>
            </div>
            <div class="open_main-page_stage-element_text-block">
                <div class="open_main-page_stage-element_year">
                    2012 год
                </div>
                <div class="open_main-page_stage-element_header">
                    ДОВЕРИЕ ПОКУПАТЕЛЯ
                </div>
                <div class="open_main-page_stage-element_description">
                    Наши бренды KATANA, ALTRON, WELT, SKIPER, BRADO, SBK, DARC, SPEC хорошо известны на потребительском рынке и пользуются высоким спросом
                </div>
            </div>
        </div>

        <div class="open_main-page_stage-element">
            <div class="open_main-page_stage-element_text-block">
                <div class="open_main-page_stage-element_year">
                    2016 год
                </div>
                <div class="open_main-page_stage-element_header">
                    ГРАНДИОЗНОЕ СТРОИТЕЛЬСТВО
                </div>
                <div class="open_main-page_stage-element_description">
                    Построены собственные помещения. Офис, склад и сервис - теперь все в одном месте
                </div>
            </div>
            <div class="open_main-page_stage-element_img-block">
                <div class="open_main-page_stage-element_round">
                    <div class="open_main-page_stage-element_round-in">
                        4
                    </div>
                </div>
                <div class="open_main-page_stage-element_line">
                </div>
                <div class="open_main-page_stage-element_arrow right">
                    @include('svg.block_2_arrow')
                    <div class="open_main-page_stage-element_white-round-wrapper">
                        <div class="open_main-page_stage-element_white-round">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="open_main-page_stage-element">
            <div class="open_main-page_stage-element_img-block">
                <div class="open_main-page_stage-element_round">
                    <div class="open_main-page_stage-element_round-in">
                        5
                    </div>
                </div>
                <div class="open_main-page_stage-element_line">
                </div>
                <div class="open_main-page_stage-element_arrow left">
                    @include('svg.block_2_arrow')
                    <div class="open_main-page_stage-element_white-round-wrapper">
                        <div class="open_main-page_stage-element_white-round">
                        </div>
                    </div>
                </div>
            </div>
            <div class="open_main-page_stage-element_text-block">
                <div class="open_main-page_stage-element_year">
                    2021 год
                </div>
                <div class="open_main-page_stage-element_header">
                    СТАВКА НА ПАРТНЕРСТВО
                </div>
                <div class="open_main-page_stage-element_description">
                    Расширяем ассортимент продукции, ввод дополнительных партнерских программ, активное маркетинговое продвижение
                </div>
            </div>
        </div>

    </div>
</div>

<div class="open_main-page_block-4-structure">

    <div class="container">

        <div class="open_main-page_block-4-structure_title">
            Структура компании
        </div>

        <div class="open_main-page_structure-element img-left">

            <div class="open_main-page_structure-element_img-block">
                <img src="{{ asset('/assets/img/structure_zakup.jpg') }}">
            </div>
            <div class="open_main-page_structure-element_text-block">
                <h3>ОТДЕЛ ЗАКУПОК</h3>
                <p>Задачи отдела закупок — это поиск лучших поставщиков оборудования и инструмента для наших брендов</p>
                <p>Отдел закупок определяет достойного поставщика по принципу взаимной экономической заинтересованности, учитывая следующие требования к Поставщику и предлагаемой продукции:</p>
                <p>
                    <span class="red-round"></span>положительная история работы Поставщика<br>
                    <span class="red-round"></span>гарантированное высокое качество предлагаемой продукции<br>
                    <span class="red-round"></span>востребованность продукции Поставщика на рынке<br>
                    <span class="red-round"></span>справедливая цена, соответствующая тенденциям на рынке<br>
                    <span class="red-round"></span>присутствие товара в продаже у известных операторов рынка<br>
                    <span class="red-round"></span>бесперебойная поставка продукции и соответствующих документов<br>
                    <span class="red-round"></span>продукция пользуется спросом у покупателей и удовлетворяет по цене
                </p>

            </div>

        </div>

        <div class="open_main-page_structure-element img-right">

            <div class="open_main-page_structure-element_text-block">
                <h3>ОТДЕЛ ПРОДАЖ И ДИСТРИБУЦИИ</h3>
                <p>Сотрудники отдела оптовых продаж постоянно стремятся к максимальному результату в существующих условиях</p>
                <p>Все сотрудники данного отдела задействованы в решении ключевых задач компании и ее собственников:</p>
                <p>
                    <span class="red-round"></span>поддержание партнерских отношений с дилерами<br>
                    <span class="red-round"></span>повышение объемов сбыта и наращивание прибыли<br>
                    <span class="red-round"></span>расширение дилерской сети<br>
                    <span class="red-round"></span>рост капитализации и т.п
                </p>
            </div>
            <div class="open_main-page_structure-element_img-block">
                <img src="{{ asset('/assets/img/structure_prodazh.jpg') }}">
            </div>

        </div>


        <div class="open_main-page_structure-element">

            <div class="open_main-page_structure-element_img-block">
                <img src="{{ asset('/assets/img/structure_logist.jpg') }}">
            </div>
            <div class="open_main-page_structure-element_text-block">
                <h3>ОТДЕЛ ЛОГИСТИКИ</h3>
                <p>Сотрудники отдела логистики координируют логистическую деятельность компании</p>
                <p>Они оптимизируют расходы на грузоперевозку оборудования следующим образом:</p>
                <p>
                    <span class="red-round"></span>формирование и осуществление контроля на всех этапах за транспортной цепочкой, включая определение способа транспортировки<br>
                    <span class="red-round"></span>оптимизация маршрута с учетом большого количества внешних факторов<br>
                    <span class="red-round"></span>координация работы участников грузоперевозок<br>
                    <span class="red-round"></span>исключение большого влияния транспортных расходов на себестоимость продукции
                </p>
            </div>

        </div>

        <div class="open_main-page_structure-element">

            <div class="open_main-page_structure-element_text-block">
                <h3>ОТДЕЛ МАРКЕТИНГА И РЕКЛАМЫ</h3>
                <p>Основной целью отдела маркетинга и рекламы, как и бизнеса в целом, является увеличение доли компании на рынке, повышение объемов продаж и прибыли, взаимовыгодное партнерство с дилерами.</p>
                <p>Наша компания поддерживает свою дилерскую сеть следующими маркетинговыми программами:</p>
                <p>
                    <span class="red-round"></span>партнерские рекламные акции<br>
                    <span class="red-round"></span>digital продвижение в интернете<br>
                    <span class="red-round"></span>видео консультант Youtube<br>
                    <span class="red-round"></span>нанесение рекламных изображений на легковые и грузовые машины<br>
                    <span class="red-round"></span>оформление интерьера / экстерьера торгового объекта партнера<br>
                    <span class="red-round"></span>изготовление полиграфии и сувенирной продукции
                </p>
            </div>
            <div class="open_main-page_structure-element_img-block">
                <img src="{{ asset('/assets/img/structure_reklama.jpg') }}">
            </div>

        </div>

        <div class="open_main-page_structure-element">

            <div class="open_main-page_structure-element_img-block">
                <img src="{{ asset('/assets/img/structure_sklad.jpg') }}">
            </div>
            <div class="open_main-page_structure-element_text-block">
                <h3>СКЛАД</h3>
                <p>На нашем складе организована электронная информационная система, которая позволяет получать данные о наличии товара в режиме реального времени. Организован автоматический двусторонний обмен данными между программами учета товаров</p>
                <p>В систему введена полноразмерная топология склада в разрезе логических зон, стеллажей, ячеек, на складе организовано динамическое адресное хранение</p>
                <p>Персонал склада оснащен радио-терминалами сбора данных. Каждая операция комментируется сотрудником через интерфейс терминала, а система управляет действиями сотрудников посредством электронных заданий и фиксирует каждую операцию в базе данных, что позволяет планировать и реализовывать отгрузки на программном уровне</p>

            </div>

        </div>

        <div class="open_main-page_structure-element">

            <div class="open_main-page_structure-element_text-block">
                <h3>СЕРВИСНОЕ ОБСЛУЖИВАНИЕ</h3>
                <p>Высококвалифицированные сотрудники отдела сервисного обслуживания оказывают консультационные услуги по ремонту оборудования и сделают все возможное для быстрого и качественного ремонта и снабжение запчастями</p>
                <p>Сервис - одна из ключевых функций компании и уже более 19 лет наши сотрудники предоставляют услуги по сервису и ремонту</p>
                <p>Компания открыла 7 собственных сервисных центов в каждом областном городе страны, а также имеет партнерскую сеть по ремонту техники в менее крупных городах, что дает дополнительные гарантии надежности нашим партнерам</p>
            </div>
            <div class="open_main-page_structure-element_img-block">
                <img src="{{ asset('/assets/img/structure_service.jpg') }}">
            </div>

        </div>

    </div>
</div>

<div class="open_main-page_block-5-map">

    <div class="container">

        <div class="open_main-page_block-5-map_title">
            Мы на карте
        </div>
        <div class="open_main-page_map">
            <div class="cover js-cover" title="Жми для активации карты"></div>
            <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A8-bZi6ddmti9v5LpgeoxwoOj456qIdLH&amp;source=constructor" width="100%" height="600" frameborder="0"></iframe>
        </div>

    </div>

</div>

@endsection

@section('scripts')
@parent

{{--<script type="text/javascript" src="{{ asset('assets/js/map_activation.js') }}"> </script>--}}
{{--<script type="text/javascript" src="{{ asset('assets/js/jquery-ui.min.js') }}"> </script>--}}
{{--<script type="text/javascript" src="{{ asset('assets/js/jquery-ui-touch.js') }}"> </script>--}}
{{--<script type="text/javascript" src="{{ asset('assets/js/main_page_slider.js') }}"> </script>--}}

@endsection
