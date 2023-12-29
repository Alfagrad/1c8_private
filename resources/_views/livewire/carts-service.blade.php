@extends('livewire.carts')

@section('tables')
    <x-cart.table-service title="Запчасти"
          :orderItems="collect($orderItems)->filter(fn($orderItem) => $orderItem->item->is_component == 1)"
    ></x-cart.table-service>
@endsection

@section('order')
    <div class="space-y-4">
        <div class="flex space-x-4">
            <div class="flex flex-col space-y-2">
                <label class="flex flex-col">
                    Фамилия, Имя, Отчество клиента *:
                    <input wire:model="form.name" type="text" class="border p-2 mt-2" placeholder="Иванов Иван Иванович">
                    @error('form.name') <span class="msg-error">{{ $message }}</span> @enderror
                </label>
                <label class="flex flex-col">
                    Телефон(ы) клиента *:
                    <input wire:model="form.phone" type="text" class="border p-2 mt-2" placeholder="+375 29 654 32 10">
                    @error('form.phone') <span class="msg-error">{{ $message }}</span> @enderror
                </label>
                <label class="flex flex-col relative">
                    Изделие *: (Начните вводить название, затем выберите из списка)
                    <input wire:model="form.itemName" type="text" class="border p-2 mt-2" id="js-item-search" placeholder="Начните вводить название">
                    @error('form.itemName') <span class="msg-error">{{ $message }}</span> @enderror
                    <input wire:model="form.item1cId" type="hidden" id="item_1c_id">
                    <div class="item-serch_result-block absolute top-20 max-h-96 bg-white border border-gray-400 overflow-auto space-y-2 p-2" style="display: none;"></div>
                </label>
                <label class="flex flex-col">
                    Серийный номер изделия *:
                    <input wire:model="form.serial" type="text" class="border p-2 mt-2" placeholder="sn12345abc6789">
                    @error('form.serial') <span class="msg-error">{{ $message }}</span> @enderror
                </label>
                <label class="flex flex-col">
                    Дата продажи *:
                    <input wire:model="form.buyDate" type="date" class="border p-2 mt-2" placeholder="ДД.ММ.ГГГГ">
                    @error('form.buyDate') <span class="msg-error">{{ $message }}</span> @enderror
                </label>
                <label class="flex flex-col">
                    Неисправность *:
                    <textarea wire:model="form.fault" type="text" class="border p-2 mt-2" placeholder="Не работает"></textarea>
                    @error('form.fault') <span class="msg-error">{{ $message }}</span> @enderror
                </label>
                <label class="flex flex-col">
                    Результат диагностики *:
                    <textarea wire:model="form.diagnostic" type="text" class="border p-2 mt-2" placeholder="Замена подшипника"></textarea>
                    @error('form.diagnostic') <span class="msg-error">{{ $message }}</span> @enderror
                </label>
            </div>

            <div class="flex flex-col space-y-2">
                <p>
                    Загрузите изображения (минимум 1 изображение):<br>
                    <span style="font-size: 0.75em;">(максимальный размер файла 10МБ, тип &laquo;.jpg&raquo;)</span>
                </p>
                @for($i = 1; $i <=5; $i++)
                    <p>
                        Изображение {{$i}} {{$i==1?'*':''}}:<br>
                        <input type="file" wire:model="form.photos" class="" {{$i==1?'required':''}}>
                        @if($i==1) @error('form.photos') <span class="msg-error">{{ $message }}</span> @enderror @endif
                    </p>
                @endfor
                <p>После отправки заказа отредактировать изображения можно будет в &laquo;Кабинете&raquo;, вкладка &laquo;Мои заказы&raquo;.</p>
            </div>

            <div class="flex flex-col space-y-2">
                <div>
{{--                    <p>Способ доставки:</p>--}}
{{--                    @foreach($this->deliveries as $delivery)--}}
{{--                        <label>--}}
{{--                            <input type="radio" required name="delivery_type" value="{{$delivery->id}}"--}}
{{--                                   data-delivery_discount="{{$delivery->action}}"--}}
{{--                            @if($delivery->id == $deliveryTypes->first()->id)  @endif>{{$delivery->text}}--}}
{{--                        </label>--}}
{{--                    @endforeach--}}
                </div>

{{--                <p>Выбрать пункт доставки:</p>--}}
{{--                <a href="" class="add-wrapp js-link-add-address">Добавить новый адрес</a>--}}
                <label>
                    Дополнительный комментарий к заказу
                    <textarea wire:model="comment" class="border p-2 mt-2"></textarea>
                </label>

            </div>
        </div>

        <input wire:click="store" class="button _red py-4 px-8 bg-main text-white m-auto hover:cursor-pointer" value="Отправить заказ">
    </div>

    {{--    <form class="wrapper w-cart-info-form" method="post" enctype="multipart/form-data" id="form-cart" action="/order/create">--}}
    {{--        <div class="cart-form-cell" style="margin-top: 20px">--}}
    {{--            <div class="input">--}}

    {{--                Фамилия, Имя, Отчество клиента *:--}}
    {{--                <input class="thin" type="text" name="client_name"--}}
    {{--                       placeholder="Иванов Иван Иванович" required>--}}
    {{--                Телефон(ы) клиента *:--}}
    {{--                <input class="thin" type="text" name="client_phone"--}}
    {{--                       placeholder="+375 29 654 32 10" required>--}}

    {{--                <div class="item-searh_block">--}}
    {{--                    Изделие *: <span style="font-size: 0.75em;">(Начните вводить название, затем выберите из списка)</span>--}}
    {{--                    <input class="thin" type="text" name="item_name"--}}
    {{--                           id="js-item-search"--}}
    {{--                           placeholder="начните вводить название"--}}
    {{--                           onfocus="this.removeAttribute('readonly');"--}}
    {{--                           autocomplete="off"--}}
    {{--                           readonly--}}
    {{--                           required>--}}
    {{--                    <div class="item-serch_result-block" style="display: none;"></div>--}}

    {{--                </div>--}}
    {{--                <input type="hidden" name="item_1c_id" id="item_1c_id" value="0">--}}

    {{--                Серийный номер изделия *:--}}
    {{--                <input class="thin" type="text" name="item_sn"--}}
    {{--                       placeholder="sn12345abc6789" required>--}}
    {{--                Дата продажи *:--}}
    {{--                <input class="thin" type="date" name="item_sale_date"--}}
    {{--                       required>--}}
    {{--                Неисправность *:--}}
    {{--                <textarea name="item_defect" placeholder="Не работает" required></textarea>--}}
    {{--                Результаты диагностики *:--}}
    {{--                <textarea name="item_diagnostic" placeholder="Замена подшипника" required></textarea>--}}

    {{--            </div>--}}
    {{--        </div>--}}

    {{--        <div class="cart-form-cell" style="margin-top: 20px;">--}}
    {{--            <div class="wrapper">--}}

    {{--                <div class="cart-form-pics">--}}
    {{--                    <p>--}}
    {{--                        Загрузите изображения (минимум 1 изображение):<br>--}}
    {{--                        <span style="font-size: 0.75em;">--}}
    {{--                                            (максимальный размер файла 10МБ, тип &laquo;.jpg&raquo;)--}}
    {{--                                        </span>--}}
    {{--                    </p>--}}

    {{--                    @if(session('no_pic'))--}}

    {{--                        <p style="color: red;">Загрузка изображения 1 - обязательна!</p>--}}

    {{--                    @endif--}}

    {{--                    @if ($errors->any())--}}

    {{--                        <p style="color: red;">Ошибка! Попробуйте еще раз.</p>--}}

    {{--                    @endif--}}
    {{--                    <p>--}}
    {{--                        Изображение 1 *:<br>--}}
    {{--                        <input type="file" name="image[1]" class="js-service-pic" required>--}}
    {{--                    </p>--}}
    {{--                    <p>--}}
    {{--                        Изображение 2:<br>--}}
    {{--                        <input type="file" name="image[2]" class="js-service-pic">--}}
    {{--                    </p>--}}
    {{--                    <p>--}}
    {{--                        Изображение 3:<br>--}}
    {{--                        <input type="file" name="image[3]" class="js-service-pic">--}}
    {{--                    </p>--}}
    {{--                    <p>--}}
    {{--                        Изображение 4:<br>--}}
    {{--                        <input type="file" name="image[4]" class="js-service-pic">--}}
    {{--                    </p>--}}
    {{--                    <p>--}}
    {{--                        После отправки заказа отредактировать изображения можно будет в &laquo;Кабинете&raquo;, вкладка &laquo;Мои заказы&raquo;.--}}
    {{--                    </p>--}}
    {{--                </div>--}}

    {{--            </div>--}}
    {{--        </div>--}}

    {{--        <div class="cart-form-cell" style="margin-top: 20px; float: right;">--}}
    {{--            <div class="wrapper">--}}
    {{--                <p>Способ доставки:</p>--}}

    {{--                @foreach($deliveryTypes as $dType)--}}
    {{--                    <label>--}}
    {{--                        <input type="radio" required name="delivery_type" value="{{$dType->id}}"--}}
    {{--                               data-delivery_discount="{{$dType->action}}"--}}
    {{--                        @if($dType->id == $deliveryTypes->first()->id)  @endif>{{$dType->text}}--}}
    {{--                    </label>--}}
    {{--                @endforeach--}}

    {{--            </div>--}}
    {{--            <div class="wrapper delivery-option-block">--}}
    {{--                <p>Выбрать пункт доставки:</p>--}}
    {{--                @foreach($profile->address as $addr)--}}
    {{--                    <label><input type="radio" name="delivery_address" value="{{$addr->id}}"--}}
    {{--                                  @if($addr->id == $profile->address->first()->id) checked @endif>{{$addr->address}}--}}
    {{--                        <span>{{$addr->comment}}</span>--}}
    {{--                    </label>--}}
    {{--                @endforeach--}}

    {{--                <a href="" class="add-wrapp js-link-add-address">Добавить новый адрес</a>--}}
    {{--                <div class="wrapper w-add-wrapp-form js-add-address-block" style="display: none">--}}
    {{--                    <div class="section-name secondary">Добавить адрес</div>--}}
    {{--                    <div class="row">--}}
    {{--                        <div class="input">--}}
    {{--                            <label>Адрес<span>*</span></label>--}}
    {{--                            <input class="thin" type="text" name="address"--}}
    {{--                                   placeholder="г. Минск, ул. Пушкина 17 офис 41">--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="input">--}}
    {{--                        <label>Дополнительный комментарий</label>--}}
    {{--                        <input class="thin" type="text" name="comment"--}}
    {{--                               placeholder="Въезд со стороны ул.Ленина через шлагбаум">--}}
    {{--                    </div>--}}
    {{--                    <div class="input">--}}
    {{--                        <a href="" class="button _red js-send-address-block">Добавить</a>--}}
    {{--                        <a href="" class="class button _gray js-cancel-address-block">Отменить</a>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="wrapper w-add-wrapp-form">--}}
    {{--                    <div class="input">--}}
    {{--                        <label>Дополнительный комментарий к заказу</label>--}}
    {{--                        <textarea name="comment_to_order"></textarea>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="cart-form-cell w-cart-total-price" style="width: 100%; background-color: transparent;">--}}
    {{--            <div class="w-cart-total-price-cell" style="height: inherit;">--}}
    {{--                <div class="total-sale-info" style="display: none;"><b></b></div>--}}
    {{--                <input type="hidden" name="total_savings" value="0">--}}
    {{--                <div class="total-price-info" style="display: none;">Итого к оплате: <b>0,00 руб</b></div>--}}
    {{--                <input type="hidden" name="total_price" value="0">--}}
    {{--                <input type="hidden" name="cart_id" value="{{$cartId}}">--}}
    {{--                <input type="submit" class="button _red" value="Отправить заказ">--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </form>--}}

@endsection

{{--@vite(['resources/js/cart_item_search.js'])--}}
