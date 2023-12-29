<section class="s-page-branding">
    <div class="container">
        <div class="wrapper white-bg-wrapper page-inset-frame">
            <div class="wrapper breadcrumbs">
                @if(isset($breadcrumbs))
                    <a typeof="v:Breadcrumb" href="/" rel="v:url" property="v:title">Главная</a>
                    <?php $path = ''; ?>
                    @foreach($breadcrumbs as $k => $v)
                        @if($v)
                            <?php $path .= $v ?>
                            <a typeof="v:Breadcrumb" rel="v:url" property="v:title" href="{{$path}}">{{$k}}</a>
                        @else
                            <span>{{$k}}</span>
                        @endif
                    @endforeach
                @endif
            </div>
            @if(isset($pageName))
                <h1>{{$pageName}}</h1>
            @endif

            @if(isset($cartArray))
                <div class="w-dropper w-dropper-width-auto w-dropper-cart ">
                    <div class="w-dropper-hovered w-dropper-cart cart-select">
                        <div class="name" id="js-total-in-cart">{!! $cartArray[$cartId] !!}</div>
                        @foreach($cartArray as $key => $value)
                            @if($key != $cartId)
                                <div class="button" style="position: relative">

                                    <a  href="/cart/{{$key}}">{!! $value !!}</a>

                                    <span style="   margin-left: auto; position: absolute;
                                        right:4px;">
                                        @if($key != 0)
                                        <a  title="Удалить корзину" href="/cart/deleteCart/{{$key}}" onClick="
                                            return confirm('Удалить корзину и весь товар в этой корзине?')">x</a>
                                        @endif
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
            @if(isset($addCart))
                <div class=" button _green">


                    <a href=""
                       class="roll _add_cart cart_white _hidden-info js-b-new-cart"
                       data-item_id="123"
                        >Создать корзину
                        <div class="cursor-hover-info">Создать новую корзину
                        </div>
                    </a>
                </div>
            @endif
            @if(isset($cartId) && $cartId != 0 && $cartId != null)
                <div class=" button _red">
                    <a href="/cart/deleteCart/{{$cartId}}/main" class="cart_white" onClick="
                                            return confirm('Удалить корзину и весь товар в этой корзине?')"
                    >Удалить корзину
                        <div class="cursor-hover-info">Кликни, чтобы удалить корзину
                        </div>
                    </a>
                </div>


            @endif
        </div>
    </div>
</section>

