<td class="td-pcs">

@if($item->count > 0)

    <div class="pcs-controll _minus js-item-remove-from-cart">-</div>

    <input 
        type="number"
        name="item_count"
        data-item_id="{{$item->id}}"
        id="item-{{$item->id}}"
        class="table-price-input"
        value="@if(isset($idToCart[$item->id])){{ $idToCart[$item->id] }}@else{{ '0' }}@endif"
        step="{{ $item->packaging }}" 
        {{-- step_index = "0" --}}
        onfocus="this.removeAttribute('readonly');" readonly autocomplete="off"
    >

    <div class="pcs-controll _plus js-item-add-to-cart">+</div>

    @if($item->packaging > 1)
    <div class="_hidden-info js-item-add-to-cart-package" data-block_id="{{$item->packaging}}" onclick="update({{$item->id}}, {{$item->packaging}})">
        В упаковке {{$item->packaging}} шт.
        <div class="cursor-hover-info-bottom ">Жми, чтобы добавить  {{$item->packaging}} шт.</div>
    </div>
    @endif

@endif

</td>

                                    @if($item->count > 0)
                                        @if($item->count > 10)
                                            <td class="td-valible">
                                                <div class="icon _yes _verylot _hidden-info">
                                                    В наличии 
                                                    @if(Auth::user()->role_id == '5' || Auth::user()->role_id == '1')
                                                    <br><strong>{{ $item->count }} шт.</strong>
                                                    @endif
                                                    <div class="cursor-hover-info">Доступно более 10шт</div>
                                                </div>
                                            </td>
                                        @elseif($item->count >= 5 and $item->count <= 10 )
                                            <td class="td-valible">
                                                <div class="icon _yes _lot _hidden-info">
                                                    В наличии
                                                    @if(Auth::user()->role_id == '5' || Auth::user()->role_id == '1')
                                                    <br><strong>{{ $item->count }} шт.</strong>
                                                    @endif
                                                    <div class="cursor-hover-info">на складе {{$item->count}}шт</div>
                                                </div>
                                            </td>
                                        @else
                                            <td class="td-valible">
                                                <div class="icon _yes  _hidden-info">
                                                    В наличии
                                                    @if(Auth::user()->role_id == '5' || Auth::user()->role_id == '1')
                                                    <br><strong>{{ $item->count }} шт.</strong>
                                                    @endif
                                                    <div class="cursor-hover-info">на складе {{$item->count}} шт</div>
                                                </div>
                                            </td>
                                        @endif
                                    @elseif($item->count_type == 2)
                                        <td class="td-valible">
                                            <div class="icon _no _reserved _hidden-info">Резерв
                                                <div class="cursor-hover-info">Звоните</div>
                                            </div>
                                        </td>
                                    @elseif($item->count_type == 3)
                                        <td class="td-valible">
                                            <div class="icon _no _hidden-info">{{$item->count_text}}
                                                <div class="cursor-hover-info">Поступит {{$item->count_text}}</div>
                                            </div>
                                        </td>
                                    @elseif($item->count_type == 4)
                                        <td class="td-valible">
                                            <div class="icon _no _reserved _hidden-info">Нет
                                                <div class="cursor-hover-info">Нет на складе</div>
                                            </div>
                                        </td>
                                    @endif


