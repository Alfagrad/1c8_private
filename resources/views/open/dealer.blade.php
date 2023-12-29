@extends('layouts.open')

@section('content')

<div class="open_dealer-page">

    <div class="url-line">

        <div class="container">

            <a href="{{ asset('/') }}">Главная</a> - <span>Дилерам</span>

        </div>

    </div>

    <div class="open_dealer-page_top-pic-block">
        <div class="container">

            <img src="{{ asset('assets/img/brands_top_pic.jpg') }}" class="open_dealer-page_top-pic" alt="Территория Альфасад">

            <div class="open_dealer-page_top-pic-shading"></div>

            <div class="open_dealer-page_top-pic-text-block">
                <div class="open_dealer-page_top-pic-text">
                    <p>Наша компания обеспечивает комплексную поставку широкого ассортимента строительной, садовой техники и инструмента под узнаваемыми брендами на выгодных для вас условиях.</p>
                    <p>Мы оказываем сервисную поддержку и маркетинговую помощь в продвижении ваших продаж.</p>
                </div>
            </div>

        </div>
    </div>

    <div class="open_dealer-page_advantages">
        
        <div class="container">
            
            <h2>СТАНЬ НАШИМ ПАРТНЕРОМ НА ВЫГОДНЫХ УСЛОВИЯХ!<br>ПРЕИМУЩЕСТВА РАБОТЫ С НАМИ:</h2>

            <div class="open_dealer-page_advantages-block">

                <div class="open_dealer-page_advantages-block-left">

                    <div class="open_dealer-page_advantages-element">
                        
                        <div class="open_dealer-page_advantages_img-block">
                            
                            <div class="open_dealer-page_advantages_img-background">

                                <div class="open_dealer-page_advantages_svg">
                                    @include('svg.adv_1')
                                </div>

                            </div>

                        </div>

                        <div class="open_dealer-page_advantages_text">
                            В нашей команде нет случайных людей, мы тщательно подбираем сотрудников в соответствии с принципами корпоративной культуры. <strong>Наши работники ценят нашу компанию и её клиентов, ценят вас.</strong>
                        </div>

                    </div>
                    
                    <div class="open_dealer-page_advantages-element">
                        
                        <div class="open_dealer-page_advantages_img-block">
                            
                            <div class="open_dealer-page_advantages_img-background">

                                <div class="open_dealer-page_advantages_svg">
                                    @include('svg.adv_2')
                                </div>

                            </div>

                        </div>

                        <div class="open_dealer-page_advantages_text">
                            Являясь лидером в поставках крупногабаритной садовой и строительной техники на рынке РБ, наша компания формирует тренды и задает ритм другим игрокам рынка. <strong>Наше предложение — ваша очевидная выгода.</strong>

                        </div>

                    </div>

                    <div class="open_dealer-page_advantages-element">
                        
                        <div class="open_dealer-page_advantages_img-block">
                            
                            <div class="open_dealer-page_advantages_img-background">

                                <div class="open_dealer-page_advantages_svg">
                                    @include('svg.adv_3')
                                </div>

                            </div>

                        </div>

                        <div class="open_dealer-page_advantages_text">
                            Мы постоянно разрабатываем новые и актуализируем уже имеющиеся процессы, подстраиваемся под требования и изменения рынка. <strong>Выбор нашей компании — это выбор в пользу вашего комфорта.</strong>
                        </div>

                    </div>

                </div>
                
                <div class="open_dealer-page_advantages-block-right">

                    <div class="open_dealer-page_advantages-element">
                        
                        <div class="open_dealer-page_advantages_img-block">
                            
                            <div class="open_dealer-page_advantages_img-background">

                                <div class="open_dealer-page_advantages_svg">
                                    @include('svg.adv_4')
                                </div>

                            </div>

                        </div>

                        <div class="open_dealer-page_advantages_text">
                            Ваш клиент — это только ваш клиент. Мы не составляем конкуренцию своим дилерам по своим же брендам через собственные сопутствующие бизнесы (интернет-магазин, торговые объекты, отдел прямых продаж). <strong>Мы помогаем вам увеличить ваши продажи.</strong>
                        </div>

                    </div>
                    
                    <div class="open_dealer-page_advantages-element">
                        
                        <div class="open_dealer-page_advantages_img-block">
                            
                            <div class="open_dealer-page_advantages_img-background">

                                <div class="open_dealer-page_advantages_svg">
                                    @include('svg.adv_6')
                                </div>

                            </div>

                        </div>

                        <div class="open_dealer-page_advantages_text">
                            <strong>Наша цель — довольный клиент.</strong> Мы сделаем все, чтобы превзойти ваши ожидания, неважно, представляете вы крупный гипермаркет или у вас розничная точка на рынке.
                        </div>

                    </div>

                    <div class="open_dealer-page_advantages-element">
                        
                        <div class="open_dealer-page_advantages_img-block">
                            
                            <div class="open_dealer-page_advantages_img-background">

                                <div class="open_dealer-page_advantages_svg">
                                    @include('svg.adv_5')
                                </div>

                            </div>

                        </div>

                        <div class="open_dealer-page_advantages_text">
                            <strong>Наши партнеры довольны работой с нами.</strong> Индекс лояльности компании - 87% (NPS по результатам опроса 2021).
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="open_dealer-page_pic-2-block">
        <div class="container">

            <img src="{{ asset('assets/img/brands_pic_2.jpg') }}" class="open_dealer-page_pic-2">

            <div class="open_dealer-page_pic-2-shading"></div>

            <div class="open_dealer-page_pic-2-text-block">
                <div class="open_dealer-page_pic-2-text">
                    МЫ ПРЕДЛАГАЕМ ВАМ
                </div>
            </div>

        </div>
    </div>

    <div class="open_dealer-page_offer">
        
        <div class="container">

            <div class="open_dealer-page_offer-block">
                
                <div class="open_dealer-page_offer-block-left">

                    <div class="open_dealer-page_offer_element">

                        <h3>Конкурентный товар</h3>

                        <p><span>@include('svg/ptichka_ico')</span>Широкий ассортимент садового и строительного оборудования, инструмента, представленного узнаваемыми брендами в нескольких ценовых сегментах.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Оптимальное соотношение цена/качество и сервисная поддержка.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Регулярный контроль МРЦ.</p>

                    </div>

                </div>

                <div class="open_dealer-page_offer-block-right">

                    <div class="open_dealer-page_offer_element">

                        <h3>Выгодные условия работы</h3>

                        <p><span>@include('svg/ptichka_ico')</span>Понятная ценовая политика и система скидок.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Возможность работы в отсрочку платежа или на условиях реализации.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Возможность вернуть неликвид.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Индивидуальный подход к каждому клиенту.</p>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="open_dealer-page_pic-3-block">
        <div class="container">

            <img src="{{ asset('assets/img/brands_pic_3.jpg') }}" class="open_dealer-page_pic-3">

            <div class="open_dealer-page_pic-3-shading"></div>

            <div class="open_dealer-page_pic-3-text-block">

                <div class="open_dealer-page_pic-3-text">

                    <h3>СКЛАД</h3>

                    <p><span class="round"></span>Собственный современный оснащенный складской комплекс под управлением информационной системы WMS для обеспечения автоматизации процессов, точности и скорости сборок ваших заказов.</p>
                    <p><span class="round"></span>Удобное расположение и подъезд, наличие парковочных мест.</p>
                    <p><span class="round"></span>Выписка документов в шаговой доступности от склада.</p>
                    <p><span class="round"></span>Помощь в загрузке крупногабаритной техники в ваш транспорт сотрудниками склада.</p>

                </div>

            </div>

        </div>
    </div>


    <div class="open_dealer-page_offer">
        
        <div class="container">

            <div class="open_dealer-page_offer-block">
                
                <div class="open_dealer-page_offer-block-left">

                    <div class="open_dealer-page_offer_element">

                        <h3>Своевременную регулярную бесплатную доставку</h3>

                        <p><span>@include('svg/ptichka_ico')</span>Собственный современный автопарк.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Доставка в каждый областной и районный город минимум раз в неделю.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Возможность доставки день в день по г. Минску.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Возможность осуществить индивидуальную доставку по указанному вами адресу и времени.</p>

                    </div>

                </div>

                <div class="open_dealer-page_offer-block-right">

                    <div class="open_dealer-page_offer_element">

                        <h3>Маркетинговую поддержку</h3>

                        <p><span>@include('svg/ptichka_ico')</span>Программа компенсации затрат на совместную рекламу.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Возможность проведения совместных маркетинговых мероприятий для формирования лояльности конечного покупателя к вашей торговой точке.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Предоставление торгового оборудования и POS- материалов для продвижения ваших продаж.</p>

                    </div>

                </div>

            </div>

        </div>
    </div>

    <div class="open_dealer-page_pic-4-block">
        <div class="container">

            <img src="{{ asset('assets/img/brands_pic_4.jpg') }}" class="open_dealer-page_pic-4">

            <div class="open_dealer-page_pic-4-shading"></div>

            <div class="open_dealer-page_pic-4-text-block">

                <div class="open_dealer-page_pic-4-text">

                    <h3>Сервисную поддержку</h3>

                    <p><span class="round"></span>Собственный сервисный центр и сеть сервисов-партнеров для быстрого и качественного гарантийного и негарантийного обслуживания и ремонта.</p>
                    <p><span class="round"></span>Отдел рекламаций для оперативного решения вопросов, связанных с заменой бракованного товара в торговой точке.</p>
                    <p><span class="round"></span>Забор/доставка ремонтов, замена и возврат брака транспортом нашей компании прямо из вашей торговой точки.</p>
                    <p><span class="round"></span>Информирование о статусе заказ-наряда ремонтов, переданных в сервисный центр, через смс, электронную почту, личный кабинет на сайте alfastok.by.</p>

                </div>

            </div>

        </div>
    </div>

    <div class="open_dealer-page_offer last">
        
        <div class="container">

            <div class="open_dealer-page_offer-block">
                
                <div class="open_dealer-page_offer-block-left">

                    <div class="open_dealer-page_offer_element">

                        <h3>Дилерский сайт</h3>

                        <p><span>@include('svg/ptichka_ico')</span>Доступ к сайту только для дилеров и только после подтверждения регистрации администратором.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Возможность регистрации нескольких пользователей от одного юр.лица.</p>
                        <p><span>@include('svg/ptichka_ico')</span>100% гарантия наличия заявленного ассортимента в наличии на складе.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Возможность получить всю необходимую информацию по продуктам компании и сервису, увидеть новинки и поучаствовать в акциях компании.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Возможность сформировать и отправить заказ, просмотреть историю заказов.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Возможность работы с несколькими корзинами.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Возможность отслеживать статус ремонтов.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Возможность самостоятельной настройки рассылок.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Возможность настройки и печати обычных и акционных ценников.</p>

                    </div>

                </div>

                <div class="open_dealer-page_offer-block-right">

                    <div class="open_dealer-page_offer_element">

                        <h3>Комфорт во взаимодействии</h3>

                        <p><span>@include('svg/ptichka_ico')</span>Персональный менеджер для каждого клиента компании.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Возможность получить квалифицированную консультацию по продукции компании по телефону, факсу, электронной почте, через мессенджеры, а также при посещении офиса компании.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Скорость обработки заказа и обратная связь от менеджера занимает не более 1 часа.</p>
                        <p><span>@include('svg/ptichka_ico')</span>Предварительная отправка необходимых вам документов на электронную почту еще до прихода товара к вам на склад или торговую точку.</p>
                        <p><span>@include('svg/ptichka_ico')</span>СМС и E-mail информирование по всем важным аспектам нашего с вами сотрудничества.</p>

                    </div>

                </div>

            </div>

        </div>
    </div>

    <div class="open_dealer-page_brand-logo-block">
        <div class="container">
            <img src="{{ asset('storage/brand-logo/skiper-logo.jpg') }}" alt="skiper-logo">
            <img src="{{ asset('storage/brand-logo/brado-logo.jpg') }}" alt="brado-logo">
            <img src="{{ asset('storage/brand-logo/katana-logo.jpg') }}" alt="katana-logo">
            <img src="{{ asset('storage/brand-logo/sbk-logo.jpg') }}" alt="sbk-logo">
            <img src="{{ asset('storage/brand-logo/spec-logo.jpg') }}" alt="spec-logo">
            <img src="{{ asset('storage/brand-logo/welt-logo.jpg') }}" alt="welt-logo">
            <img src="{{ asset('storage/brand-logo/darc-logo.jpg') }}" alt="darc-logo">
            <img src="{{ asset('storage/brand-logo/altron-logo.jpg') }}" alt="altron-logo">
        </div>
    </div>




</div>

@endsection



@section('scripts')
@parent



@endsection