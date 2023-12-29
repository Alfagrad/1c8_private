@props(['item'])

{{--@if(!$cart_item_line ?? true || $is_service)--}}

    <div class="catalog-item-line_expensive-block">

        <div
            title="Если дорого, нажми!"
            class="catalog-item-line_expensive-link js-want-cheaper"
            data-item_id="{{ $item->id }}"
            data-item_name="{{ $item->name }}"
        >
            !
        </div>

    </div>

{{--@endif--}}
