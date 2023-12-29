@php
    if (isset($item->{'1c_id'})) {
         $id_item = $item->{'1c_id'};
     } 
@endphp

@if(isset($item_analogs[$id_item]))
    {{-- Узнаем, есть ли аналоги в наличии --}}
    @foreach($item_analogs[$id_item] as $item)
        @php $item = $item->getItem; @endphp
            @if($item->count <= 0)
                @continue
            @else
                @php $GLOBALS['i']++; @endphp
            @endif
    @endforeach

    {{-- Если есть аналоги в наличии, выводим --}}
    @if($GLOBALS['i'])
        <span class="js-spare-analogs spare-analogs_link" data-id="{{ $id_item }}">Аналоги</span>
    @endif

@endif

