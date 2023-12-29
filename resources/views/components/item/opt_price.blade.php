@props(['item'])

<div class="catalog-item-line_opt-price-block js-opt">

    @if($item->discounts->count() > 0)

        <div class="catalog-item-line_opt-price">

            <div class="catalog-item-line_mobile-price-title">
                опт руб
            </div>

            <div class="red">
                {{ price(percent($item->price_rub, $item->discount_values?->first()->value)) }}
            </div>

            <div class="normal-price @if($item->discount_values?->first()->condition == 1){{ 'line-through' }}@endif">
                {{ price($item->price_rub) }}
            </div>

        </div>

    @else

        <div class="catalog-item-line_opt-price">

            <div class="catalog-item-line_mobile-price-title">
                опт руб
            </div>

            {{ price($item->price_rub) }}
        </div>

    @endif

</div>
