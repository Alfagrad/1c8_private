@extends('livewire.carts')

@section('cart-orders')
    <div class="cart-title">
        Корзина <strong>&laquo;<span class="_js-cart-name">{{$cartOrderName}}</span>&raquo;</strong>:
        <strong><span class="_js-count-items">{{ $this->carts->count() }}</span></strong> позиций,
        <strong><span class="_js-count-all-items">{{ $this->carts->sum('count') }}</span></strong>
        товаров, на сумму <strong><span class="_js-result-price">{{price($sum = collect($orderItems)->sum('item_sum_price'))}}</span></strong> руб.
    </div>

    <livewire:cart-orders :cartOrders="$this->cartOrders" />
@endsection

@section('tables')
{{--    {{dd($orderItems)}}--}}
    <x-cart.table title="Товары"
{{--                  :carts="$carts->filter(fn($cart) => $cart->item->is_component == 0)"--}}
                  :orderItems="collect($orderItems)->filter(fn($orderItem) => $orderItem->item->is_component == 0)"
                  :wire:key="rand(0, 1000)"
    ></x-cart.table>

    <x-cart.table title="Запчасти"
{{--                  :carts="$carts->filter(fn($cart) => $cart->item->is_component == 1)"--}}
                  :orderItems="collect($orderItems)->filter(fn($orderItem) => $orderItem->item->is_component == 1)"
                  :wire:key="rand(0, 1000)"
    ></x-cart.table>
@endsection

@section('relocate')
    <div class="cart-total-line flex justify-between">

        <div x-data="{open: false}" @mouseover="open=true" @mouseout="open=false">

            <div class="cart-manipulate-block">

                <div class="title-block">
                    <div class="title">Переместить/копировать товары...</div>
                    <div class="arrow">
                        @include('svg.phones_arrow_ico')
                    </div>
                </div>

                <div x-show="open" x-cloak x-transition.duration.500ms class="drop-down-block ">

                    <x-cart.relocate title="Перенести в:" :cartOrders="$this->cartOrders" :$cartOrderId method="moveTo"></x-cart.relocate>
                    <x-cart.relocate title="Копировать в:" :cartOrders="$this->cartOrders" :$cartOrderId method="copyTo"></x-cart.relocate>

                    <div class="relocate-block">
                        <div class="relocate-links-block">
                            <div wire:click="delete()" class="relocate-link">
                                Удалить выбранное из корзины
                            </div>
                        </div>
                    </div>

                    @if($cartOrderId != 0)
                        <div class="relocate-block">
                            <div class="relocate-links-block">
                                <div wire:click="swap" class="relocate-link">
                                    Поменять местами с Основной корзиной
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

            </div>

        </div>

        <div class="result-info-block">
            <div>Итого:</div>
            <div><span class="">{{$this->carts->sum('sum_weight')}}</span> кг</div>
            <div><span class="">{{price(collect($orderItems)->sum('item_sum_price'))}}</span> руб.</div>
        </div>

    </div>
@endsection

@section('order')
    <div class="input-wrapper">

        <div class="input-block">

            <div class="title">Выберите способ доставки:</div>

            @if($this->addresses->count() > 0)

                <div class="input-element">

                    <label>
                        <input type="radio" wire:model="form.delivery" value="Доставка" required>
                        Доставка
                    </label>

                </div>

            @endif

            <div class="input-element">

                <label>
                    <input type="radio" wire:model="form.delivery" value="Самовывоз" required {{$this->addresses->count() <= 0 ? ' checked' : ''}} >
                    Самовывоз
                </label>
            </div>

            @error('form.delivery') <div class="msg-error">{{ $message }}</div> @enderror

        </div>

        @if($this->addresses->count() > 0)

            <div class="input-block">

                <div class="title">Выберите адрес доставки:</div>

                @foreach($this->addresses as $address)

                    <div class="input-element">

                        <label>

{{--                            <input type="radio" name="delivery_address" wire:click="$set('address', '{{$address}}')" required>--}}
                            <input type="radio" name="delivery_address" wire:model="form.address" value="{{$address}}" required>
                            {{ $address }}

                        </label>

                    </div>

                @endforeach

            </div>
            @error('form.address') <div class="msg-error">{{ $message }}</div> @enderror

        @endif

        <div class="delivery-comment">

            <div class="title">Дополнительный комментарий к заказу:</div>

            <textarea wire:model='comment' class="border" name="comment_to_order"></textarea>

            @error('form.comment') <div class="msg-error">{{ $message }}</div> @enderror

        </div>

    </div>
    <div class="input-wrapper">

        <div class="input-block">

            <div class="title">Выберите соглашение:</div>

            @if($this->agreementsCommon->count())

                <div class="title">Типовое:</div>

                @foreach($this->agreementsCommon as $agreement)

                    <div class="input-element ">

                        <label>

                            <input
                                type="radio"
                                name="calc_type"
                                wire:click="$set('form.agreementTypeUuid', '{{ $agreement->uuid }}')"
                                required
                            >

                            {{ $agreement->name }}

                            @if($agreement->formula)
                                (
                                @if($agreement->formula > 0)
                                    {{ '+' }}
                                @else
                                    {{ 'до ' }}
                                @endif{{ $agreement->formula }}%
                                )
                            @endif

                        </label>

                    </div>

                @endforeach

            @endif

            @if($this->agreementsPersonal->count())

                <div class="title">Индивидуальное:</div>

                @foreach($this->agreementsPersonal as $agreement)

                    <div class="input-element js-personal-agreement">

                        <label>

                            <input
                                type="radio"
                                name="calc_type"
                                wire:click="$set('form.agreementTypeUuid', '{{ $agreement->uuid }}')"
                                required
                            >

                            {{ $agreement->name }}

                            @if($agreement->formula)
                                (
                                @if($agreement->formula > 0)
                                    {{ '+' }}
                                @else
                                    {{ 'до ' }}
                                @endif{{ $agreement->formula }}%
                                )
                            @endif

                        </label>

                    </div>

                @endforeach

            @endif

        </div>

        @error('form.agreementTypeUuid') <div class="msg-error">{{ $message }}</div> @enderror

    </div>
    <div class="input-wrapper">

        <div class="result-block">

{{--            @if($sum > $sums->sum())--}}
{{--                <div class="saving">--}}
{{--                    <div class="">Ваша экономия: <span>{{price($sum - collect($orderItems)->sum('item_sum_price'))}}</span> руб.</div>--}}
{{--                </div>--}}
{{--            @endif--}}

            <div class="to-be-paid">
                Итого к оплате: <span class="">{{price(collect($orderItems)->sum('item_sum_price'))}}</span> руб.
            </div>

            <div class="submit-button">
                <input type="hidden" name="cart_id" value="">
                <button wire:click="store" class="">Отправить заказ</button>
            </div>

            <div class="note">
                * Внимание!<br>Заказы на сумму менее {{price(config('settings.min_price_to_order'))}} руб не принимаем!
            </div>

        </div>

    </div>
@endsection
