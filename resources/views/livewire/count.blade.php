<div>

    <div class="catalog-item-line_input-block">

        <div wire:click="minus" class="catalog-item-line_input-control minus button {{--js-item-remove-from-cart--}}">
            -
        </div>

        <input
                wire:model.live="count"
                data-step="{{ $packaging = $item->packaging ?? 1 }}"
        >

        <div wire:click="plus" class="catalog-item-line_input-control plus button {{--js-item-add-to-cart--}} ">
            +
        </div>

    </div>

    @if($packaging > 1)

        <div
                class="catalog-item-line_packagin-str {{--js-item-add-to-cart-package js-input-packaging plus--}}"
                data-block_id="{{ $packaging }}"
                data-item_id="{{ $item->id }}"
                title="Жми, чтобы добавить {{$packaging}} шт."
        >
            В упаковке {{ $packaging }} шт.
        </div>

    @endif

</div>
