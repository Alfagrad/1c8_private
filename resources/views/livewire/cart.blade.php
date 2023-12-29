<div>

    <x-item.line :$item class="cart-item-line {{$cart->item->amount <= 0 ? 'bg-error-light' : '' }}">

        <x-slot:move>
            <input type="checkbox" title="Отметить для копирования/переноса"/>
            {{--                        <div class="checkbox-block">--}}
            {{--                            <div class="checker-wrapper">--}}
            {{--                                <div class="checker js-item-checker" title="Отметить для копирования/переноса">--}}
            {{--                                    <span>&#10004;</span>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
        </x-slot:move>

        <x-slot:checkAmount>
            @if($cart->item->amount <= 0)
                <div style='color: red; font-weight: bold;'>! Уточните наличие этой позиции у Вашего менеджера !</div>
            @endif
        </x-slot:checkAmount>

        <x-slot:calcPrice>
            <div class="purcent">
                <div>
                    <div class="catalog-item-line_mobile-price-title">
                        скидка
                    </div>
                    <span class="">{{ $discount ?? 0 }}</span>%
                </div>
            </div>

            <div class="calculated-price">
                <div>
                    <div class="catalog-item-line_mobile-price-title">
                        цена расч
                    </div>
                    <div class="">{{ price($calculatedPrice) }}</div>
                </div>
            </div>
        </x-slot:calcPrice>

        <x-slot:count>
            <livewire:count :$cart :$item :key="$item->uuid"/>
            {{--                        <x-item.count :$item :$cart></x-item.count>--}}
        </x-slot:count>

        <x-slot:weight>
            <x-item.weight :$item></x-item.weight>
        </x-slot:weight>

        <x-slot:sum>
            <div class="total-price">
                <div>
                    <div class="catalog-item-line_mobile-price-title">
                        итого
                    </div>
                    {{--                                <div class="js-line-total-price">--}}
                    <div class="">
                        {{price($sum)}}
                    </div>
                </div>
            </div>
        </x-slot:sum>

        <x-slot:delete>
            <div class="del-item">
                <span class="">+</span>
            </div>
        </x-slot:delete>

    </x-item.line>

</div>
