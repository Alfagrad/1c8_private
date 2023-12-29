@props(['item'])

@if($item->is_component == 2)

    <div class="catalog-item-line_opt-price-block">

        <div class="catalog-item-line_opt-price">

            <div class="catalog-item-line_mobile-price-title">
                нормо-часов
            </div>

            {{ $item->norm_hour }}
        </div>

    </div>

@endif
