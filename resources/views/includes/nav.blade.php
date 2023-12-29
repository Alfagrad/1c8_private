<nav class="main-navigation">

    <div class="container">

        <a href="{{ asset('/catalogue') }}" class="main-navigation_catalogue-button js-catalog-button" title="Каталог продукции">
            Каталог
            <span class="main-navigation_arrow js-catalog-arrow">
                @include('svg.phones_arrow_ico')
            </span>

        </a>

        <div class="main-navigation_links-block">

            <div class="main-navigation_burger" id="js-burger">
                <div class="main-navigation_burger_line-block" id="js-lines">
                    <div class="hr-1"></div>
                    <div class="hr-2"></div>
                    <div class="hr-3"></div>
                </div>
                <div class="main-navigation_burger_header">
                    Меню
                </div>
            </div>

            <ul class="main-navigation_category-links" id="_js-links-block">

                <li class="main-navigation_catalog-link">
                    <a href="{{ route('catalogue.index') }}" class="main-navigation_catalogue-button _js-catalog-button" title="Каталог продукции">
                        Каталог
                        <span class="main-navigation_arrow _js-catalog-arrow">
                            @include('svg.phones_arrow_ico')
                        </span>
                    </a>
                </li>

                @foreach($menu as $m)

                    @if($m->link)

                        @if(!$m->subMenu->count())

                            <li
                                class="main-navigation_with-link-category _js-link-category @if($m->link == '/'.Request::segment(1).'/'.Request::segment(2) || $m->link =='/'.Request::segment(1)){{ 'active' }}@endif"
                            >
                                <a href="{{ $m->link }}" @if($m->new_window) target="_blank" @endif>
                                    {{ $m->name }}
                                </a>
                            </li>

                        @else

                            <li
                                x-data="{open: false}"
                                x-on:mouseover="open=true"
                                x-on:mouseover.away="open=false"
{{--                                x-on:mouseout="open=false"--}}
                                class="main-navigation_no-link-category _js-no-link-category"
                            >

                                <a href="#" class="main-navigation_no-link-category-name _js-no-link-name">
                                    {{ $m->name }}
                                    @include('svg.phones_arrow_ico')
                                </a>

                                <ul x-show="open" x-cloak class="main-navigation_sub-category-links _js-sub-links">

                                    @foreach($m->subMenu as $sM)

                                        <li
                                            class="@if($sM->link == '/'.Request::segment(1).'/'.Request::segment(2)){{ 'active' }}@endif"
                                        >
                                            <a href="{{ $sM->link }}" @if($m->new_window) target="_blank" @endif>
                                                {{ $sM->name }}
                                            </a>
                                        </li>

                                    @endforeach

                                </ul>

                            </li>

                        @endif

                    @endif

                @endforeach

            </ul>

            {{--            <div class="main-navigation_mini-cart-block w-cart js-mini-cart-block ">--}}

{{--            <livewire:mini-cart :$carts></livewire:mini-cart>--}}

            {{--            </div>--}}

        </div>


    </div>
</nav>
