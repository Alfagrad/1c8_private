@php

    if ($item->analog_container_uuid) {
        dd(1);

        // собираем массив uuid-ов аналогов
        $analog_uuid_arr = $item->analogs->pluck('analog_uuid')->toArray();

        // собираем аналоги
        $analog_items = App\Item::whereIn('uuid', $analog_uuid_arr)
            ->where([['uuid', '!=', $item->uuid], ['in_archive', 0]])
            ->where(function ($query) {
                $query->where('amount', '>', 0)->orWhere('locked', '>', 0);
            })
            ->get();

        if ($analog_items->count()) {
            $analog_button = true;
        } else {
            $analog_button = false;
        }

        // берем количество товара родителя и код
        $parent_count = $item->amount + $item->locked;
        $parent_id = $item->id_1c;
    } else {
        $analog_button = false;
    }

@endphp

@include('item.line.dealer')

{{--@include('catalog.snippets.new_item_line', [--}}
{{--    'analog_button' => $analog_button,--}}
{{--])--}}

{{--@if (isset($analog_items) && $analog_items->count())--}}

{{--    @foreach ($analog_items as $item)--}}


{{--        @include('catalog.snippets.new_item_line', [--}}
{{--            'analog_line' => true,--}}
{{--            'parent_count' => $parent_count,--}}
{{--            'parent_id_1c' => $parent_id,--}}
{{--        ])--}}
{{--    @endforeach--}}

{{--@endif--}}
