@php
    if (isset($item->{'1c_id'})) {
         $id_item = $item->{'1c_id'};
     } 
@endphp

@if(isset($item_analogs[$id_item]))

    @if($GLOBALS['i'])

        @foreach($item_analogs[$id_item] as $item)
            @php
                $item = $item->getItem;
                $is_analog_line = 1; // метка, что выводятся аналоги
            @endphp

            @if($item->count <= 0) @continue @endif

@include('service.includes.item_block_line')

        @endforeach

    @endif

    @php
        $GLOBALS['i'] = 0;
        $is_analog_line = 0;
    @endphp

@endif
