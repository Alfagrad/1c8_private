<section class="s-main-navigation fixed">
    <div class="container">
        <div class="wrapper w-main-navigation">
            <div class="left">
                <div class="b-main-catalog  b-main-catalog__pc">
                    <div class="icon">
                        <div>
                            <div class="dot"></div>
                            <div class="line"></div>
                        </div>
                        <div>
                            <div class="dot"></div>
                            <div class="line"></div>
                        </div>
                        <div>
                            <div class="dot"></div>
                            <div class="line"></div>
                        </div>
                        <div>
                            <div class="dot"></div>
                            <div class="line"></div>
                        </div>
                    </div>
                    <a href="" class="a-toggle-catalog _toggle _active">Свернуть каталог</a>
                    <a href="" class="a-toggle-catalog _untoggle">Развернуть каталог</a>
                </div>
                <div class="b-main-catalog b-main-catalog__mobile">
                    <div class="icon">
                        <div>
                            <div class="dot"></div>
                            <div class="line"></div>
                        </div>
                        <div>
                            <div class="dot"></div>
                            <div class="line"></div>
                        </div>
                        <div>
                            <div class="dot"></div>
                            <div class="line"></div>
                        </div>
                        <div>
                            <div class="dot"></div>
                            <div class="line"></div>
                        </div>
                    </div>
                    <a href="" class="a-toggle-catalog _toggle _active">Развернуть каталог</a>
                    <a href="" class="a-toggle-catalog _untoggle">Свернуть каталог</a>
                </div>
            </div>
            <div class="right">
                <div class="wrapper w-filters-table">
                    <div class="input">
                        <input type="text" class="thin js-filter-search-button" placeholder="Фильтр по слову" name="filter_word">
                    </div>

                    @if(!isset($search))

                    <div class="input item-sort">
                        <select name="catalog_sort">
                            <option value="alpha" selected >по алфавиту</option>
                            <option value="low-cost">дешевые</option>
                            <option value="high-cost">дорогие</option>
                            <option value="popular">популярные</option>
                            <option value="new">новые</option>
                            <option value="old">старые</option>
                        </select>
                    </div>

                    @endif

                    <div class="input" style="width: 50px;">
                        <a href="" class="button js-b-usd ">USD</a>
                        <a href="" class="button js-b-byn _active">BYN</a>
                    </div>
                    <div class="input">
                        <a href="" class="button js-b-mrp">МРЦ</a>
                    </div>

                    <div class="input catalog-page_filter-line_v-line"></div>

                    <div class="input">
                        <a href="" class="button js-but-is-news" title="Показать Новинки">Новинки</a>
                    </div>

                    <div class="input">
                        <a href="" class="button js-but-is-action" title="Показать акционные и товары со скидкой">Акции</a>
                    </div>

{{--                     <div class="input">
                        <a href="" class="button js-b-is-cheap" title="Фильтр по %">%</a>
                    </div>
 --}}
                </div>
                <nav>
                    <div class="close-main-menu">
                        <a href="">&times;</a>
                    </div>
                    <ul class="main-menu">
                        @foreach($menu as $m)
                            @if($m->link)
                                @if(!$m->subMenu->count())
                                    <li @if(('/'.Request::segment(1).'/'.Request::segment(2) == $m->link) OR (Request::segment(1) == '' AND $m->link=='/')) class="_active" @endif><a href="{{$m->link}}" @if($m->new_window) target="_blank" @endif>{{$m->name}}</a></li>
                                @else
                                    <li class="li_dropper download-icon">
                                        <a href="{{$m->link}}">{{$m->name}}</a>
                                        <div class="inset">
                                            <ul class="dropper">
                                                @foreach($m->subMenu as $sM)
                                                    <li><a href="{{$sM->link}}" @if($m->new_window) target="_blank" @endif>{{$sM->name}}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </li>
                                @endif

                            @else
                                @if(!$m->subMenu->count())
                                    <li><span>{{$m->name}}</span></li>
                                @else
                                    <li class="li_dropper download-icon">
                                        <a href="">{{$m->name}}</a>
                                        <div class="inset">
                                            <ul class="dropper">
                                                @foreach($m->subMenu as $sM)
                                                    <li><a href="{{$sM->link}}" @if($m->new_window) target="_blank" @endif >{{$sM->name}}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </li>
                                @endif


                            @endif

                        @endforeach
                    </ul>
                </nav>
                <div class="right">
                    <div class="w-cart">
                        {{-- <a href="/" class="close _hidden-info js-clear-cart">&times;<div class="cursor-hover-info">Очистить корзину</div></a> --}}
                        <a href="{{route('cartView')}}" title="В корзину">
                            <div class="icon">
                                <div class="pcs">{{$inCart['count']}}</div>
                            </div>
                            <div class="cart-count">
                                {{ number_format($inCart['price'], 2, '.', '') }} руб.
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>