@props(['item', 'cart' => null])

{{--TODO добать проверку сервиса в каталоге--}}

{{--@if((\Request::route()->getName() == 'newCatalogView' && !$is_service) || \Request::route()->getName() == 'itemCard' || \Request::route()->getName() == 'newCartView')--}}

{{--    <div class="catalog-item-line_input-block-wrapper">--}}

{{--        <livewire:count :$cart :$item :key="$item->uuid"/>--}}

{{--        @if($item->amount > 0 || isset($cart_item_line) || $is_service)--}}

{{--            <div class="catalog-item-line_input-block">--}}

{{--                <div class="catalog-item-line_input-control minus --}}{{--js-item-remove-from-cart--}}{{--">--}}
{{--                    ---}}
{{--                </div>--}}

{{--                <input--}}
{{--                        type="number"--}}
{{--                        name="item_count"--}}
{{--                        class="js-count-input"--}}
{{--                        value="{{ !empty($cart) ? $cart->count : 0 }}"--}}
{{--                        data-step="{{ $packaging = $item->packaging ?? 1 }}"--}}
{{--                        data-id_1c="{{ $item->id_1c }}"--}}
{{--                        data-rout_name="{{ \Request::route()->getName() }}"--}}
{{--                        onfocus="this.removeAttribute('readonly');"--}}
{{--                        readonly--}}
{{--                        autocomplete="off"--}}
{{--                >--}}

{{--                <div class="catalog-item-line_input-control plus --}}{{--js-item-add-to-cart--}}{{-- ">--}}
{{--                    +--}}
{{--                </div>--}}

{{--            </div>--}}

{{--            @if($packaging > 1)--}}

{{--                <div--}}
{{--                        class="catalog-item-line_packagin-str --}}{{--js-item-add-to-cart-package js-input-packaging plus--}}{{--"--}}
{{--                        data-block_id="{{ $packaging }}"--}}
{{--                        data-item_id="{{ $item->id }}"--}}
{{--                        title="Жми, чтобы добавить {{$packaging}} шт."--}}
{{--                >--}}
{{--                    В упаковке {{ $packaging }} шт.--}}
{{--                </div>--}}

{{--            @endif--}}

{{--        @endif--}}

{{--    </div>--}}

{{--@endif--}}
