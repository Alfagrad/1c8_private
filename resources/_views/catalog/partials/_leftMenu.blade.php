<div class="wrapper white-bg-wrapper w-main-catalog _toggled js-main-catalog" id="left-menu">

    <div class="w-left-catalog">
        <ul class="main-catalog">

            <div class="left-catalog_promo-links">
                <a href="{{ asset('new-items') }}" title="Все новинки">
                    <span class="left-catalog_promo-links_new-sign">NEW</span>
                    Все новинки ({{ $new_items_count }})
                </a>
                <a href="{{ asset('all-actions') }}" title="Все акции">
                    <span class="left-catalog_promo-links_action-sign">АКЦИЯ</span>
                    Все акции и скидки
                </a>
            </div>


            @foreach($categories as $c)

                @if($c->{'1c_id'} == '20070') @continue @endif

                <li class="li_dropper">
                    <label>
                        <a href="{{asset('catalog/'.$c{'1c_id'})}}" class="catalog">
                            {{$c->name}}
                        </a>
                    </label>
                    @if($c->subCategory->count())
                        <div class="a-main-catalog-arrow"></div>
                        <div class="b-main-catalog-dropper"></div>
                    @endif
                </li>

                <div class="inset">
                    <ul class="dropper">
                        <?
                        $countSubCategory = 0;
                        foreach ($c->subCategory as $cat) {
                            $countSubCategory += $cat->subCategory->count();
                        }
                        ?>
                        @if($c->subCategory->count() and $countSubCategory)
                            @foreach($c->subCategory as $cs)

                                @if($cs->subCategory->count())

                                    <li class="category-name " onclick="location.href = '{{asset('catalog/'.$cs{'1c_id'})}}';"><label>
                                            <table style="margin: 0px">
                                                <tr>
                                                    <td>
                                                        <div class="wrapper">
                                                            @if($cs->image_path != null && file_exists(public_path('storage/'.$cs->image_path)))
                                                                <a href="{{asset('catalog/'.$cs{'1c_id'})}}">
                                                                    <img
                                                                            src="{{asset('storage/'.$cs->image_path)}}"
                                                                            style="max-width:40px"

                                                                            alt="">
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="wrapper red-background">
                                                            <a href="{{asset('catalog/'.$cs{'1c_id'})}}"
                                                               class="catalog category">
                                                                {{$cs->name}}
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </label>
                                    </li>

                                    @foreach($cs->subCategory as $css)
                                        <li class="l_subsubcat" onclick="location.href = '{{asset('catalog/'.$css{'1c_id'})}}';"><label>
                                                <table style="margin: 0px">
                                                    <tr>
                                                        <td>

                                                        </td>
                                                        <td>
                                                            <div class="wrapper red-background">
                                                                <a href="{{asset('catalog/'.$css{'1c_id'})}}"
                                                                   class="catalog ">
                                                                    {{$css->name}}
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </label>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="l_subcat" onclick="location.href = '{{asset('catalog/'.$cs{'1c_id'})}}';"><label>
                                            <table style="margin: 0px">
                                                <tr>
                                                    <td>
                                                        <div class="wrapper">
                                                            @if($cs->image_path != null && file_exists(public_path('storage/'.$cs->image_path)))
                                                                <a href="{{asset('catalog/'.$cs{'1c_id'})}}"
                                                                   class="catalog category">
                                                                    <img
                                                                            src="{{asset('storage/'.$cs->image_path)}}"
                                                                            style="max-width:40px"
                                                                            alt="">
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="wrapper red-background">
                                                            <a href="{{asset('catalog/'.$cs{'1c_id'})}}"
                                                               class="catalog category">
                                                                {{$cs->name}}
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>


                                        </label>
                                    </li>
                                @endif
                            @endforeach
                        @else
                            @foreach($c->subCategory as $cs)
                                <li class="l_subcat " onclick="location.href = '{{asset('catalog/'.$cs{'1c_id'})}}';"><label>
                                        <table style="margin: 0px">
                                            <tr>
                                                <td>
                                                    <div class="wrapper">
                                                        @if($cs->image_path != null && file_exists(public_path('storage/'.$cs->image_path)))
                                                            <a href="{{asset('catalog/'.$cs{'1c_id'})}}"
                                                               class="catalog category">
                                                                <img
                                                                        src="{{asset('storage/'.$cs->image_path)}}"
                                                                        style="max-width:40px"
                                                                        alt="">
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="wrapper red-background">
                                                        <a href="{{asset('catalog/'.$cs{'1c_id'})}}"
                                                           class="catalog category">
                                                            {{$cs->name}}
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </label>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            @endforeach
            {{--            <li class="li-sale">--}}
            {{--                <a href="" class="sale__link js-all-actions">Все акции и %</a>--}}
            {{--            </li>--}}

        </ul>
    </div>
</div>
