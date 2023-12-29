@props(['item'])

{{--            <div class="catalog-item-line_percent-block js-purcent" style="display: {{ $item_line_purcent_style }};">--}}
<div class="catalog-item-line_percent-block js-purcent">

    <div>

        <div class="catalog-item-line_mobile-price-title">
            %
        </div>

        @if($item->{'1c_category_id'} != '3149')  {{-- Если уцененный товар, не выводим! --}}

        {{ $item->price_mr_rub && $item->price_rub ? price(($item->price_mr_rub / $item->price_rub - 1) * 100) : 0 }} %

        @endif

    </div>

</div>
