<div class="catalog-page_catalog-lines-filters">

    <div class="catalog-lines-section_toggler-wrapper all">

        <div
            class="catalog-lines-section_toggler js-all-catalog-toggler"
            title="Скрыть-показать товары ВСЕХ категорий"
        >
            -
        </div>

    </div>

    <div class="catalog-lines-section_filters-wrapper">

        <div class="catalog-lines-section_word-filter-block js-filter-block">

            <div class="catalog-lines-section_word-filter-button hidden js-word-filter-button">

                <div class="catalog-lines-section_word-filter-ico">
                    @include('svg.word_search')
                </div>
                <div class="catalog-lines-section_word-filter-title">
                    По слову
                </div>
                <div class="catalog-lines-section_word-filter-arrow">
                    @include('svg.phones_arrow_ico')
                </div>

            </div>

            <div class="catalog-lines-section_word-filter-input js-word-filter-input">

                <input
                    type="text"
                    name="filter_word"
                    class="js-word-filter"
                    onfocus="this.removeAttribute('readonly');"
                    autocomplete="off"
                    placeholder="Фильтр по наименованию"
                >

            </div>

        </div>

        @if(profile()->isDealer())

            <div class="catalog-lines-section_item-filter-block js-filter-block">

                <div class="catalog-lines-section_item-filter-button js-item-filter-button">

                    <div class="catalog-lines-section_item-filter-ico">
                        @include('svg.item-filters')
                    </div>
                    <div class="catalog-lines-section_item-filter-title">
                        По товару
                    </div>
                    <div class="catalog-lines-section_item-filter-arrow">
                        @include('svg.phones_arrow_ico')
                    </div>

                </div>

                <div class="catalog-lines-section_item-filter-togglers-block js-item-filter-togglers">

                    <div class="catalog-lines-section_item-filter-togglers">

                        <div class="catalog-lines-section_item-filter-toggler-element js-all-button active">
                            <div class="checkbox">
                                <span>
                                    @include('svg.galochka_ico')
                                </span>
                            </div>
                            <div class="title">Все</div>
                        </div>

                        @if(Route::current()->getName() != 'newNewItems' && Route::current()->getName() != 'newAllActions')

                            <div class="catalog-lines-section_item-filter-toggler-element js-archive-button">
                                <div class="checkbox">
                                    <span>
                                        @include('svg.galochka_ico')
                                    </span>
                                </div>
                                <div class="title">+Архив</div>
                            </div>

                        @endif

                    </div>

                    <div class="catalog-lines-section_item-filter-togglers">

                        @if(Route::current()->getName() != 'newNewItems')

                            <div class="catalog-lines-section_item-filter-toggler-element js-new-button">
                                <div class="checkbox">
                                    <span>
                                        @include('svg.galochka_ico')
                                    </span>
                                </div>
                                <div class="title">Новинки</div>
                            </div>

                        @endif

                        @if(Route::current()->getName() != 'newAllActions')

                            <div class="catalog-lines-section_item-filter-toggler-element js-action-button">
                                <div class="checkbox">
                                    <span>
                                        @include('svg.galochka_ico')
                                    </span>
                                </div>
                                <div class="title">Акции</div>
                            </div>

                        @endif

                    </div>

                    @if(Route::current()->getName() != 'newNewItems' && Route::current()->getName() != 'newAllActions')

                        <div class="catalog-lines-section_item-filter-togglers">

                            <div class="catalog-lines-section_item-filter-toggler-element js-in-price-button">
                                <div class="checkbox">
                                    <span>
                                        @include('svg.galochka_ico')
                                    </span>
                                </div>
                                <div class="title">В наличии</div>
                            </div>

                            <div class="catalog-lines-section_item-filter-toggler-element js-in-way-button">
                                <div class="checkbox">
                                    <span>
                                        @include('svg.galochka_ico')
                                    </span>
                                </div>
                                <div class="title">В пути</div>
                            </div>

                            <div class="catalog-lines-section_item-filter-toggler-element js-reserve-button">
                                <div class="checkbox">
                                    <span>
                                        @include('svg.galochka_ico')
                                    </span>
                                </div>
                                <div class="title">В резерве</div>
                            </div>

                        </div>

                    @endif

                </div>

            </div>

            <div class="catalog-lines-section_view-price-block js-filter-block">

                <div class="catalog-lines-section_view-price-button js-view-price-button">

                    <div class="catalog-lines-section_view-pricer-ico">
                        @include('svg.price_togglers')
                    </div>
                    <div class="catalog-lines-section_view-price-title">
                        Цены
                    </div>
                    <div class="catalog-lines-section_view-price-arrow">
                        @include('svg.phones_arrow_ico')
                    </div>

                </div>

                @php

                    if(isset($_COOKIE['opt_state'])) {
                        if($_COOKIE['opt_state']) {
                            $button_opt_class = "active";
                        } else {
                            $button_opt_class = "";
                        }
                    } else {
                        $button_opt_class = "active";
                    }

                    if(isset($_COOKIE['purcent_state'])) {
                        if($_COOKIE['purcent_state']) {
                            $button_purcent_class = "active";
                        } else {
                            $button_purcent_class = "";
                        }
                    } else {
                        $button_purcent_class = "active";
                    }

                    if(isset($_COOKIE['mr_state'])) {
                        if($_COOKIE['mr_state']) {
                            $button_mr_class = "active";
                        } else {
                            $button_mr_class = "";
                        }
                    } else {
                        $button_mr_class = "";
                    }


                @endphp

                <div class="catalog-lines-section_view-price-togglers js-view-price-togglers">

                    <div class="catalog-lines-section_view-price-toggler-element js-opt-button {{ $button_opt_class }}">
                        <div class="checkbox">
                            <span>
                                @include('svg.galochka_ico')
                            </span>
                        </div>
                        <div class="title">ОПТ</div>
                    </div>

                    <div class="catalog-lines-section_view-price-toggler-element js-purcent-button {{ $button_purcent_class }}">
                        <div class="checkbox">
                            <span>
                                @include('svg.galochka_ico')
                            </span>
                        </div>
                        <div class="title">%</div>
                    </div>

                    <div class="catalog-lines-section_view-price-toggler-element js-mr-button {{ $button_mr_class }}">
                        <div class="checkbox">
                            <span>
                                @include('svg.galochka_ico')
                            </span>
                        </div>
                        <div class="title">МРЦ</div>
                    </div>

                </div>

            </div>

            <div class="catalog-lines-section_item-sort-block js-filter-block">

                <div class="catalog-lines-section_item-sort-button js-item-sort-button">

                    <div class="catalog-lines-section_item-sort-ico">
                        @include('svg.sort')
                    </div>
                    <div class="catalog-lines-section_item-sort-title">
                        Сортировка
                    </div>
                    <div class="catalog-lines-section_item-sort-arrow">
                        @include('svg.phones_arrow_ico')
                    </div>

                </div>

                <div class="catalog-lines-section_item-sort-togglers js-item-sort-togglers">

                    <div class="catalog-lines-section_item-sort-toggler-element js-a-z active">
                        <div class="checkbox">
                            <span>
                                @include('svg.galochka_ico')
                            </span>
                        </div>
                        <div class="title">По алфавиту</div>
                    </div>

                    <div class="catalog-lines-section_item-sort-toggler-element js-price-a-z">
                        <div class="checkbox">
                            <span>
                                @include('svg.galochka_ico')
                            </span>
                        </div>
                        <div class="title">Дешевые</div>
                    </div>

                    <div class="catalog-lines-section_item-sort-toggler-element js-price-z-a">
                        <div class="checkbox">
                            <span>
                                @include('svg.galochka_ico')
                            </span>
                        </div>
                        <div class="title">Дорогие</div>
                    </div>

                </div>

            </div>

        @endif

    </div>


</div>
