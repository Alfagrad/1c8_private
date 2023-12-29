@php($item = $c->item)
    
<tr class="js-pos-item"
    @if($item->count <= 0)
        @if(!in_array($item->{'1c_category_id'}, $service_category_array))
    style='background-color: mistyrose;'
        @endif
    @endif>
    <td class="td-image">
        @if($item->images->count())
            <a href="{{route('service-item-view', ['itemId' => $item->{'1c_id'}])}}"
               class=" hovered-product"
               data-big="{{asset($imageResize->resize($item->images->first()->path_image, 240,240))}}"
               data-hasqtip="0">
                <img src="{{asset($imageResize->resize($item->images->first()->path_image, 66))}}">
            </a>
        @else
            <img src="{{asset('upload/no-thumb.png')}}" height="66px">
        @endif
    </td>
    <td class="td-article">{{$item->code}}</td>

    <td class="td-name">
        <a href="{{ route('service-item-view', ['itemId' => $item->{'1c_id'}]) }}"
           class="name ">{{ $item->name }}</a>

        @if($item->guide_file)

            <a href="{{ asset('storage/item-images/manuals/'.$item->guide_file) }}" title="Скачать руководство по эксплуатации" class="cat_ico" target="_blank">
                <img src="{{asset('assets/img/doc_ico.png')}}">
            </a>

        @endif

        @if($item->certificate_file AND ($item->certificate_exp == '0000-00-00' OR $item->certificate_exp >= date('Y-m-d')))

            <a href="{{ asset('storage/item-images/certificates/'.$item->certificate_file) }}" title="Скачать сертификат" class="cat_ico" target="_blank">
                <img src="{{asset('assets/img/pdf_ico.png')}}">
            </a>

        @endif

{{-- @php dd($item->whereNotIn('1c_category_id', $service_category_array)->first()); @endphp --}}


        @if($item->count <= 0)
            @if(!in_array($item->{'1c_category_id'}, $service_category_array))

        <div style='color: red; font-weight: bold;'>! Уточните наличие этой позиции у Вашего менеджера !</div>

            @endif
        @endif

    </td>

    <td class="td-pcs">

        <div class="pcs-controll _minus js-item-remove-from-cart">-</div>

        <input
            type="number"
            name="item_count"
            data-item_id="{{$item->id}}"
            data-1cid="{{($item->{'1c_id'})}}"
            id="item-{{$item->id}}"
            class="table-price-input"
            value="@if(isset($c->count)){{ $c->count }}@else{{ '0' }}@endif"
            step="1" 
            onfocus="this.removeAttribute('readonly');" readonly autocomplete="off"
        >

        <div class="pcs-controll _plus js-item-add-to-cart">+</div>


    </td>

    <td class="td-weight"
        data-weight="{{$item->weight}}">{{$item->weight}} кг
    </td>

{{--     @if($item->count > 0)
        @if($item->count > 10)
            <td class="td-valible">
                <div class="icon _yes _verylot _hidden-info">
                    В наличии
                    @if(Auth::user()->role_id == '5' || Auth::user()->role_id == '1')
                    <br><strong>{{ $item->count }} шт.</strong>
                    @endif
                    <div class="cursor-hover-info">Доступно
                        более 10шт
                    </div>
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
                    <div class="cursor-hover-info">на
                        складе {{$item->count}} шт
                    </div>
                </div>
            </td>
        @endif
    @elseif($item->count_type == 2)
        <td class="td-valible">
            <div class="icon _no _reserved _hidden-info">
                Резерв
                <div class="cursor-hover-info">Звоните</div>
            </div>
        </td>
    @elseif($item->count_type == 3)
        <td class="td-valible">
            <div class="icon _no _hidden-info">{{$item->count_text}}
                <div class="cursor-hover-info">
                    Поступит {{$item->count_text}}</div>
            </div>
        </td>
    @elseif($item->count_type == 4)
        <td class="td-valible">
            <div class="icon _no _reserved _hidden-info">Нет
                <div class="cursor-hover-info">Нет на
                    складе
                </div>
            </div>
        </td>
    @endif
 --}}
    <td class="td-delete-item _hidden-info"><a href="#"
                                               class="js-delete-item-from-cart"
                                               data-1c_id="{{ $item->{'1c_id'} }}">&times;<div
                    class="cursor-hover-info">Удалить из
                корзины
            </div>
        </a></td>
</tr>
