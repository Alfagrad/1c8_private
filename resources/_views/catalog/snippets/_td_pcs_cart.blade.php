@if($item->count > 0)

    <div class="pcs-controll _minus js-item-remove-from-cart">-</div>

    <input
        type="number"
        name="item_count"
        data-item_id="{{$item->id}}"
        id="item-{{$item->id}}"
        data-1cid="{{($item->{'1c_id'})}}"
        class="table-price-input"
        value="@if(isset($c->count)){{ $c->count }}@else{{ '0' }}@endif"
        step="{{ $item->packaging }}" 
        {{-- step_index = "0" --}}
        onfocus="this.removeAttribute('readonly');" readonly autocomplete="off"
    >

    <div class="pcs-controll _plus js-item-add-to-cart">+</div>

    @if($item->packaging > 1  )
    <div class="_hidden-info js-item-add-to-cart-package"
         data-block_id="{{$item->packaging}}"
         onclick="update({{$item->id}}, {{$item->packaging}})">
        В упаковке {{$item->packaging}} шт.
        <div class="cursor-hover-info-bottom ">Жми, чтобы добавить  {{$item->packaging}} шт.</div>
    </div>
    @endif

@endif
