<div class="w-main-table">
    <div class="left">
    </div>
    <div class="right _fullwidth">
        <div class="wrapper white-bg-wrapper page-inset-frame cart">
            <div class="wrapper items-table cart-table">
                @if($products_yes)
                    <div class="w-table">
                        <div class="table-head">
                            <h5 class="search-results-status-title">Товары</h5>
                            <table>
                                <thead>
                                <tr>
                                    <td class="td-more">
                                        <div class="input">
                                            <label class="for-checkbox-cart for-checkbox-select-all"
                                                   title="выделить все позиции для копирования/перемещения в другую корзину"
                                                   style="left: 20px;">
                                                <input type="checkbox" class="main-checkbox"
                                                       name="main-checkbox" value=""/>

                                            </label>
                                        </div>
                                    </td>
                                    <td class="td-image"></td>
                                    <td class="td-article">Код</td>
                                    <td class="td-name">Наименование</td>
                                    <td class="td-more"></td>
                                    <td class="td-sale"></td>
                                    <td class="td-price">Цена</td>
                                    <td class="td-price">Скидка / Надбавка</td>
                                    <td class="td-price">Цена расчетная
{{--                                                             <br>
                                        <span>@if($personal_discount) {{$personal_discount}}% @else
                                                0% @endif</span> --}}
                                    </td>
                                    <td class="td-pcs"></td>
                                    <td class="td-weight">Вес</td>
                                    <td class="td-price">Итого</td>
                                    <td class="td-valible"></td>
                                    <td class="td-delete-item"></td>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="table-body">
                            <table>
                                <tbody>
                                @foreach($cart as $c)
                                    @if(!$c->item)
                                        @continue
                                    @endif
                                    @if($c->item->is_component == 0)

@include('catalog.snippets.item_cart_block_line')

                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($spares_yes)
                    <div class="w-table">
                        <div class="table-head">
                            <h5 class="search-results-status-title">Запчасти</h5>
                            <table>
                                <thead>
                                <tr>
                                    <td class="td-more">
                                        <div class="input">
                                            <label class="for-checkbox-cart for-checkbox-select-all"
                                                   style="left: 20px;">
                                                <input type="checkbox" class="main-checkbox"
                                                       name="main-checkbox" value=""/>

                                            </label>
                                        </div>
                                    </td>
                                    <td class="td-article">Код</td>
                                    <td class="td-image"></td>
                                    <td class="td-name">Наименование</td>
                                    <td class="td-more"></td>
                                    <td class="td-sale"></td>
                                    <td class="td-price">Цена</td>
                                    <td class="td-price">Скидка / Надбавка</td>
                                    <td class="td-price">Цена расчетная
{{--                                                             <br>
                                        <span>@if($personal_discount) {{$personal_discount}}% @else
                                                0% @endif</span> --}}
                                    </td>
                                    <td class="td-pcs"></td>
                                    <td class="td-weight">Вес</td>
                                    <td class="td-price">Итого</td>
                                    <td class="td-valible"></td>
                                    <td class="td-delete-item"></td>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="table-body">
                            <table>
                                <tbody>
                                @foreach($cart as $c)
                                    @if(!$c->item)
                                        @continue
                                    @endif
                                    @if($c->item->is_component == 1)

@include('catalog.snippets.item_cart_block_line')

                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <div class="w-table">
                    <div class="table-body">
                        <table>
                            <tbody>
                            <tr class="tr-cart-bottom">
                                <td>
                                    <div class="w-dropper w-dropper-width-300 w-dropper-cart">
                                        <div class="w-dropper-hovered w-dropper-cart cart-select">
                                            <div class="name" style="font-size: 14px;">Переместить/копировать в корзину
                                                ...
                                            </div>

                                            <input type="hidden" value="{{$cartId}}" id="currentCart">
                                            <input type="hidden" value="1" id="cartPartial">
                                            @foreach($cartArrayForSelect as $key => $value)
                                                @if($key != $cartId)
                                                    <div class="button" style="text-align:left;"><a
                                                                href="/cart/{{$key}}" class=" change-position"
                                                                data-action="1" data-idcart="{{$key}}">Перенести
                                                            в {{$value}}</a></div>

                                                @endif
                                            @endforeach
                                            @foreach($cartArrayForSelect as $key => $value)
                                                @if($key != $cartId)


                                                    <div class="button" style="text-align:left;"><a
                                                                href="/cart/{{$key}}" class=" change-position"
                                                                data-action="2" data-idcart="{{$key}}">Скопировать
                                                            в {{$value}}</a>
                                                    </div>
                                                @endif
                                            @endforeach

                                            <div class="button"><a class=" delete-position" href="/cart/{{$key}}">Удалить
                                                    выбранные из корзины</a></div>


                                            @if($cartId != 0 && $cartId != null)
                                                <div class="button">---</div>
                                                <div class="button" style="text-align:left;">
                                                    <a href="#" class=" change-position-with-main" data-action="2"
                                                       data-idcart="{{$cartId}}">
                                                        Поменять местами с Основной корзиной</a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>


                                </td>

                                <td
                                    style="width: 46%;"
                                    class="td-total-sale-info"
                                    data-personal_discount="{{ $personal_discount }}"
                                >

                                    @if($personal_discount)

                                    Ваша персональная скидка/надбавка
                                    @if($personal_discount > 0) +@endif{{ $personal_discount }}%
                                    @if($profile->discount_text) * {{$profile->discount_text}}@endif

                                    @endif 

                                </td>

                                <td style="width: 210px;"
                                    class="td-total-weight-info js-total-weight-info">Итого: <span>0,00 кг</span>
                                </td>
                                <td style="width: 150px;" class="td-total-price js-total-price">0,00
                                    руб
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="wrapper w-clear-table">
                    <a href="{{ asset('cart/clear/'.$cartId) }}" class="a-clear-table js-clear-alert">
                        Очистить корзину <b>&laquo;{{ $cartArrayForSelect[$cartId] }}&raquo;</b>
                    </a>
                    <br>
                    <a href="{{ asset('cart/clear/all') }}" class="a-clear-table js-clear-alert">
                        Очистить <b>ВСЕ</b> корзины
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- @if($gifts->count())
    @include('cartTableGifts')
@endif
 --}}

<form class="wrapper w-cart-info-form" method="post" id="form-cart" action="/order/create"
      enctype="multipart/form-data">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <div class="cart-form-cell">
        <p><strong>Вид расчета</strong></p>

        @foreach($calcTypes as $cType)

        <label>
            <input type="radio" required name="calc_type" value="{{$cType->id}}"
                   data-calc_discount="{{$cType->action}}"
            @if($cType->id == $calcTypes->first()->id)  @endif>{{$cType->text}} @if($cType->action)
                (
                @if($cType->id == '3' || $cType->id == '8') до @endif
                {{$cType->action}}%
                )@endif
        </label>

        @endforeach
    </div>
    <div class="cart-form-cell">
        <div class="wrapper">
            <p><strong>Способ доставки:</strong></p>

            @foreach($deliveryTypes as $dType)

            <label>
                <input
                    type="radio"
                    name="delivery_type"
                    value="{{$dType->id}}"
                    data-delivery_discount="{{$dType->action}}"
                    required
                    @if($dType->id == 1)
                    class="js-pickup"
                    @else
                    class="js-no-pickup"
                    @endif
                >
                {{$dType->text}}
            </label>

            @endforeach

        </div>
        <div class="wrapper delivery-option-block">
            <p><strong>Выбрать пункт доставки:</strong></p>

            @foreach($profile->address as $addr)

            <label>
                <input
                    type="radio"
                    name="delivery_address"
                    value="{{$addr->id}}"
                    required
                    >
                {{$addr->address}}
                <span>{{$addr->comment}}</span>
            </label>

            @endforeach

            <a href="" class="add-wrapp js-link-add-address">Добавить новый адрес</a>
            <div class="wrapper w-add-wrapp-form js-add-address-block" style="display: none">
                <div class="section-name secondary">Добавить адрес</div>
                <div class="row">
                    <div class="input">
                        <label>Адрес<span>*</span></label>
                        <input class="thin" type="text" name="address"
                               placeholder="г. Минск, ул. Пушкина 17 офис 41">
                    </div>
                </div>
                <div class="input">
                    <label>Дополнительный комментарий</label>
                    <input class="thin" type="text" name="comment"
                           placeholder="Въезд со стороны ул.Ленина через шлагбаум">
                </div>
                <div class="input">
                    <a href="" class="button _red js-send-address-block">Добавить</a>
                    <a href="" class="class button _gray js-cancel-address-block">Отменить</a>
                </div>
            </div>
            <div class="wrapper w-add-wrapp-form">
                <div class="input">
                    <label>Дополнительный комментарий к заказу</label>
                    <textarea name="comment_to_order"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="cart-form-cell w-cart-total-price">
        <div class="w-cart-total-price-cell">
            <div class="total-sale-info"><b></b></div>
            <input type="hidden" name="total_savings" value="0">
            <div class="total-price-info">Итого к оплате: <b>0,00 руб</b></div>
            <input type="hidden" name="total_price" value="0">
            <input type="hidden" name="cart_id" value="{{$cartId}}">
            <input type="submit" class="button _red" value="Отправить заказ">
        </div>
    </div>
</form>

{{-- Предупреждение перед очисткой корзины --}}
<script type="text/javascript" src="{{ asset('assets/js/clean_cart_alert.js') }}"></script>

<script>
    // самовывоз, снимаем
    $('.js-pickup').click(function(){
        $('input[name="delivery_address"]').removeAttr("required").removeAttr("checked").attr('disabled', 'disabled');
        console.log('снимаем required')
    });
    // иначе, устанавливаем
    $('.js-no-pickup').click(function(){
        $('input[name="delivery_address"]').attr("required", "required").removeAttr("disabled");
        console.log('устанавливаем required')
    });
</script>