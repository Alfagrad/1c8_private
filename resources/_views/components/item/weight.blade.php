@props(['item', 'count' => 1])

<div class="weight">
    <div>
        <div class="catalog-item-line_mobile-price-title">
            вес
        </div>
        <div><span class="js-weight">{{$item->weight * $count}}</span> кг</div>
    </div>
</div>
