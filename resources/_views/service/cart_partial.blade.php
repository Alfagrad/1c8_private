<div class="w-main-table">
    <div class="left">
    </div>
    <div class="right _fullwidth" style="border: none;">
        <div class="wrapper white-bg-wrapper page-inset-frame service">
            <div class="wrapper items-table cart-table">

                                    @if($products_yes)
                                        <div class="w-table">
                                            <div class="table-head">
                                                <h5 class="search-results-status-title">Услуги</h5>
                                                <table>
                                                    <thead>
                                                    <tr>
                                                        <td class="td-image"></td>
                                                        <td class="td-article">Код</td>
                                                        <td class="td-name">Наименование</td>
                                                        <td class="td-pcs"></td>
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

                                                        @if($c->item->is_component == 2)

@include('service.includes.item_cart_block_line')

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
                                                        <td class="td-image"></td>
                                                        <td class="td-article">Код</td>
                                                        <td class="td-name">Наименование</td>
                                                        <td class="td-pcs"></td>
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

@include('service.includes.item_cart_block_line')

                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="w-table">
                                            <div class="table-head">
                                                <h5 class="search-results-status-title">Расходные материалы</h5>
                                                <table>
                                                    <thead>
                                                    <tr>
                                                        <td class="td-image"></td>
                                                        <td class="td-article">Код</td>
                                                        <td class="td-name">Наименование</td>
                                                        <td class="td-pcs"></td>
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

@include('service.includes.item_cart_block_line')

                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif

                <div class="w-table" style="display: none;">
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

                <div class="wrapper w-clear-table service">
                    <a href="#" class="a-clear-table js-clear-cart" style="display: inline-block; padding: 20px 0;">
                        Очистить корзину
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

<form class="wrapper w-cart-info-form" method="post" enctype="multipart/form-data" id="form-cart" action="/order/create">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <div class="cart-form-cell" style="display: none;">
        <p>Вид расчета</p>
{{--         @foreach($calcTypes as $cType) --}}
            <label>
                <input type="radio" required="" name="calc_type" value="9" data-calc_discount="0" checked>
{{--                 <input type="radio" required name="calc_type" value="{{$cType->id}}"
                       data-calc_discount="{{$cType->action}}"
                @if($cType->id == $calcTypes->first()->id)  @endif>{{$cType->text}} @if($cType->action)
                    (
                    @if($cType->id == '3' || $cType->id == '8') до @endif
                    {{$cType->action}}%
                    )@endif --}}
            </label>
        {{-- @endforeach --}}
    </div>
    <div class="cart-form-cell" style="margin-top: 20px">
        <div class="input">

            Фамилия, Имя, Отчество клиента *:
            <input class="thin" type="text" name="client_name"
                   placeholder="Иванов Иван Иванович" required>
            Телефон(ы) клиента *:
            <input class="thin" type="text" name="client_phone"
                   placeholder="+375 29 654 32 10" required>

            <div class="item-searh_block">
                Изделие *: <span style="font-size: 0.75em;">(Начните вводить название, затем выберите из списка)</span>
                <input class="thin" type="text" name="item_name"
                       id="js-item-search"
                       placeholder="начните вводить название"
                       onfocus="this.removeAttribute('readonly');"
                       autocomplete="off"
                       readonly
                       required>
                <div class="item-serch_result-block" style="display: none;"></div>
                
            </div>
            <input type="hidden" name="item_1c_id" id="item_1c_id" value="0">

            Серийный номер изделия *:
            <input class="thin" type="text" name="item_sn"
                   placeholder="sn12345abc6789" required>
            Дата продажи *:
            <input class="thin" type="date" name="item_sale_date"
                   required>
            Неисправность *:
            <textarea name="item_defect" placeholder="Не работает" required></textarea>
            Результаты диагностики *:
            <textarea name="item_diagnostic" placeholder="Замена подшипника" required></textarea>

        </div>
    </div>

    <div class="cart-form-cell" style="margin-top: 20px;">
        <div class="wrapper">

            <div class="cart-form-pics">
                <p>
                    Загрузите изображения (минимум 1 изображение):<br>
                    <span style="font-size: 0.75em;">
                        (максимальный размер файла 10МБ, тип &laquo;.jpg&raquo;)
                    </span>
                </p>

                @if(session('no_pic')) 

                <p style="color: red;">Загрузка изображения 1 - обязательна!</p>

                @endif

                @if ($errors->any())

                <p style="color: red;">Ошибка! Попробуйте еще раз.</p>

                @endif
                <p>
                    Изображение 1 *:<br>
                    <input type="file" name="image[1]" class="js-service-pic" required>
                </p>
                <p>
                    Изображение 2:<br>
                    <input type="file" name="image[2]" class="js-service-pic">
                </p>
                <p>
                    Изображение 3:<br>
                    <input type="file" name="image[3]" class="js-service-pic">
                </p>
                <p>
                    Изображение 4:<br>
                    <input type="file" name="image[4]" class="js-service-pic">
                </p>
            </div>

        </div>
    </div>

    <div class="cart-form-cell" style="margin-top: 20px; float: right;">
        <div class="wrapper">
            <p>Способ доставки:</p>

            @foreach($deliveryTypes as $dType)
                <label>
                    <input type="radio" required name="delivery_type" value="{{$dType->id}}"
                           data-delivery_discount="{{$dType->action}}"
                    @if($dType->id == $deliveryTypes->first()->id)  @endif>{{$dType->text}}
                </label>
            @endforeach

        </div>
        <div class="wrapper delivery-option-block">
            <p>Выбрать пункт доставки:</p>
            @foreach($profile->address as $addr)
                <label><input type="radio" name="delivery_address" value="{{$addr->id}}"
                              @if($addr->id == $profile->address->first()->id) checked @endif>{{$addr->address}}
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
    <div class="cart-form-cell w-cart-total-price" style="width: 100%; background-color: transparent;">
        <div class="w-cart-total-price-cell" style="height: inherit;">
            <div class="total-sale-info" style="display: none;"><b></b></div>
            <input type="hidden" name="total_savings" value="0">
            <div class="total-price-info" style="display: none;">Итого к оплате: <b>0,00 руб</b></div>
            <input type="hidden" name="total_price" value="0">
            <input type="hidden" name="cart_id" value="{{$cartId}}">
            <input type="submit" class="button _red" value="Отправить заказ">
        </div>
    </div>
</form>

{{-- Валидация размера и типа изображения --}}
<script type="text/javascript" src="{{ asset('assets/js/pic_validator.js') }}"></script>
