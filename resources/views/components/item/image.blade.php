@props(['item'])

@if($item->is_component != 2)

    <div class="catalog-item-line_image-wrapper @if($item->image_sm){{'js-zoom-wrapper'}}@endif" data-id-1c="{{ $item->id_1c }}">

        <div class="catalog-item-line_image">

            @if($item->image_sm)

                <img src="{{ asset('storage/ut_1c8/item-images/'.$item->image_sm) }}">

            @else

                <img src="{{ asset('upload/no-thumb.png') }}" class="no-trumb">

            @endif


            @if($item->is_new_item == 1)

                <div class="catalog-item-line_new-item-sign">NEW</div>

            @endif

        </div>

        @if($item->image_sm)

            <div class="zoom-pic js-zoom-pic" data-path="{{ $item->image_mid }}"></div>

        @endif

    </div>

@endif
