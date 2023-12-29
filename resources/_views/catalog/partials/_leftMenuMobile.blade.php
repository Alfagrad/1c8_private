<div class="wrapper white-bg-wrapper w-main-catalog _toggled js-main-catalog left-menu-mobile">

    <div class="w-left-catalog">
        <ul class="main-catalog">
            @foreach($categories as $c)
                    <li class="">
                    <label>
                        <a href="{{asset('catalog/'.$c{'1c_id'})}}" class="catalog">
                            {{$c->name}}
                        </a>
                    </label>
                        <div class="li_dropper">
                            @if($c->subCategory->count())
                                <div class="a-main-catalog-arrow"></div>
                                <div class="b-main-catalog-dropper"></div>
                            @endif
                        </div>
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
                                    <li class="category-name "><label>
                                            <table style="margin: 0px">
                                                <tr>
                                                    <td>
                                                        <div class="wrapper">
                                                            <img
                                                                    src="{{asset('storage/'.$cs->image_path)}}"
                                                                    width="60px"
                                                                    alt="">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="wrapper">
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
                                        <li class="l_subsubcat"><label>
                                                <a href="{{asset('catalog/'.$css{'1c_id'})}}" class="catalog">
                                                    {{$css->name}}
                                                </a> </label>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="l_subcat"><label>
                                            <table style="margin: 0px">
                                                <tr>
                                                    <td>
                                                        <div class="wrapper">
                                                            <img
                                                                    src="{{asset('storage/'.$cs->image_path)}}"
                                                                    width="60px"
                                                                    alt="">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="wrapper">
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
                                <li class="l_subcat " ><label>
                                        <a href="{{asset('catalog/'.$cs{'1c_id'})}}" class="catalog category">
                                            {{$cs->name}}
                                        </a> </label>
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
<script>
    $('input[type=checkbox] , select').styler();
</script>