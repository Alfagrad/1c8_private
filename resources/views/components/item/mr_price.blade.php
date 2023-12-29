@props(['item'])

{{--            <div class="catalog-item-line_mr-block js-mr" style="display: {{ $item_line_mr_style }};">--}}
<div class="catalog-item-line_mr-block js-mr">

    @if($item->{'category_id_1c'} != '3149')  {{-- Если уцененный товар, не выводим! --}}

    <div>

        <div class="catalog-item-line_mobile-price-title">
            мрц руб
        </div>
        {{ price($item->price_mr_rub) }}

    </div>

    @endif

</div>
