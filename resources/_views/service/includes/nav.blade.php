<section class="s-main-navigation">
    <div class="container">
        <div class="wrapper w-main-navigation">
            <div class="left">
                <div class="b-main-catalog">
                    <a href="{{ asset('service/') }}">
                        <div class="name">каталог</div>
                    </a>
                </div>
            </div>
            <div class="right">
                <nav>
                    <div class="close-main-menu">
                        <a href="">&times;</a>
                    </div>

                    <ul class="main-menu">
                        <li><a href="/service">Главная</a></li>
                        <li><a href="/service/delivery">Доставка</a></li>

{{--                         @foreach($menu as $m)
                            @if($m->link)
                                @if(!$m->subMenu->count())
                                    <li @if(('/'.Request::segment(1).'/'.Request::segment(2) == $m->link) OR (Request::segment(1) == '' AND $m->link=='/')) class="_active" @endif><a href="{{$m->link}}" @if($m->new_window) target="_blank" @endif>{{$m->name}}</a></li>
                                @else
                                    <li class="li_dropper download-icon">
                                        <a class="no-link" href="{{$m->link}}">{{$m->name}}</a>
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
                                        <!-- <a href="{{$m->link}}">{{$m->name}}</a> -->
                                    <li><span>{{$m->name}}</span></li>
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

                        @endforeach --}}
                    </ul>
                </nav>
                <div class="right">
                    <div class="w-cart">
                        <a href="" class="close _hidden-info js-clear-cart">&times;<div class="cursor-hover-info">Очистить корзину</div></a>
                        <a href="{{route('cartView')}}">
                            <div class="icon">
                                <div class="pcs">{{$inCart['count']}}</div>
                            </div>
{{--                             <div class="cart-count">
                                {{$inCart['price']}} руб
                            </div> --}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>