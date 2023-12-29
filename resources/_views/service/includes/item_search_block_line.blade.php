<div class="row dropper">

    <a href="{{ route($item_route, ['itemId' => $item->{'1c_id'}]) }}" class="">

        @php
            $name = preg_replace($patterns, $replace, $item->name);
        @endphp

        {!! $name !!}
    </a>
</div>

<div class="inset">

    @if($item->images->count())

    <a href="{{ route($item_route, ['itemId' => $item->{'1c_id'}]) }}" class="w-image ">
        <img src="{{ asset($imageResize->resize($item->images->first()->path_image, 150,150)) }}">
    </a>

    @endif

    <div class="name">
        <a href="{{ route($item_route, ['itemId' => $item->{'1c_id'}]) }}" class="">{{ $item->name }}</a>
    </div>

    @if($type == 'spares')

    <div class="article">
        Код: <span>{{ $item->code }}</span>
    </div>

    <div class="price-line">
        <div class="in-stock">

            @if($item->count > 0)
                @if($item->count > 10)
                    <div class="td-valible">
                        <div class="icon _yes _verylot">
                            В наличии
                            @if(Auth::user()->role_id == '5' || Auth::user()->role_id == '1')
                            <br><strong>{{ $item->count }} шт.</strong>
                            @endif
                        </div>
                    </div>
                @elseif($item->count >= 5 and $item->count <= 10 )
                    <div class="td-valible">
                        <div class="icon _yes _lot">
                            В наличии
                            @if(Auth::user()->role_id == '5' || Auth::user()->role_id == '1')
                            <br><strong>{{ $item->count }} шт.</strong>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="td-valible">
                        <div class="icon _yes">
                            В наличии
                            @if(Auth::user()->role_id == '5' || Auth::user()->role_id == '1')
                            <br><strong>{{ $item->count }} шт.</strong>
                            @endif
                        </div>
                    </div>
                @endif
            @elseif($item->count_type == 2)
                <div class="td-valible">
                    <div class="icon _no _reserved">Резерв</div>
                </div>
            @elseif($item->count_type == 3)
                <div class="td-valible">
                    <div class="icon _no">{{$item->count_text}} </div>
                </div>

            @elseif($item->count_type == 4)
                <div class="td-valible">
                    <div class="icon">Нет на складе</div>
                </div>

            @endif
        </div>
    </div>

    <div class="centered">
        <div class="td-pcs">
            <div class="pcs-controll _minus js-item-remove-from-cart">-</div>
            <input
                type="number"
                name="item_count"
                data-item_id="{{$item->id}}"
                data-1cid="{{($item->{'1c_id'})}}"
                id="item-{{$item->id}}"
                class="table-price-input"
                value="@if(isset($c->count)){{ $c->count }}@else{{ '0' }}@endif"
                step="{{ $item->packaging }}" 
                {{-- step_index = "0" --}}
                onfocus="this.removeAttribute('readonly');" readonly autocomplete="off"
            >
            <div class="pcs-controll _plus js-item-add-to-cart">+</div>
        </div>
    </div>

    @endif

</div>
