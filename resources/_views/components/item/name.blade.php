@props(['item', 'discount_str'])

@php
    if(isset($item->is_spare) && $spare) {
        $scheme_item_line_class = "scheme_item_line";
        $scheme_item = true;
    } else {
        $scheme_item_line_class = "";
        $scheme_item = false;
    }
@endphp

<a href="{{ route('item.index', $item->id_1c) }}" class="catalog-item-line_item-name">

    <span class="catalog-item-line_item-code">
        {{ $item->id_1c }}
    </span>

    @if($scheme_item)

        <span class="catalog-item-line_spare-name spare js-scheme-num" data-scheme_id={{ $spare->scheme_id }}>
            {{ (isset($analog_line) && $analog_line) ? '' : $spare->spare_name }}
        </span>

        <span class="catalog-item-line_item-code spare">
            {{ (isset($analog_line) && $analog_line) ? '' : $spare->position }}
        </span>

        <span class="catalog-item-line_item-code spare">
            {{ (isset($analog_line) && $analog_line) ? '' : $spare->amount }}
        </span>

    @endif

    <span class="catalog-item-line_name">

        @if(trim($item->discounts->count() > 0))
            <div class="action-sing">АКЦИЯ</div>
        @elseif ($item->spec_price == 1)
            <div class="action-sing spec">Спецпредложение</div>
        @endif
        <span class="name">{{ $item->name }}</span>

    </span>

{{--    @if($discount_str)--}}
{{--        <span class="js-action-string" style="color: red;">--}}
{{--            {{ $discount_str }}--}}
{{--        </span>--}}

{{--    @endif--}}

</a>

@if($item->adjustable == 1)

    <div class="cat_ico" title="В перечне">
        <div class="adjustable">713</div>
    </div>

@endif

@if($item->importer == 1)

    <div class="cat_ico" title="Первый импортер">
        <div class="importer">1</div>
    </div>

@endif

@if(trim($item->youtube))

    @php
        $youtube_array = array_diff(explode(';', trim($item->youtube)), array('', NULL, false));
        $youtube_code = '';
    @endphp

    @foreach($youtube_array as $youtube_link)

        @php
            if(count(explode('=', $youtube_link)) > 1)
                $youtube_code .= explode('=', $youtube_link)[1].";";
            else
                $youtube_code .= explode('.be/', $youtube_link)[1].";";
        @endphp

    @endforeach

    <span
        class="cat_ico js_video_link"
        title="Смотреть видео о товаре на Youtube"
        video="{{ $youtube_code }}"
    >
                        <img src="{{ asset('assets/img/youtube_ico.png') }}">
                    </span>

@endif

@if($item->guides->count())

    @foreach($item->guides as $guide)

        <a
            class="cat_ico"
            href="{{ asset('storage/ut_1c8/item-guides/'.$guide->file) }}"
            title="Скачать руководство по эксплуатации"
            target="_blank"
        >
            <img src="{{asset('assets/img/doc_ico.png')}}">
        </a>

    @endforeach

@endif

<a
    class="cat_ico pricetag"
    href="{{ asset('pricetag/form/'.$item->id_1c) }}"
    title="Сформировать ценник"
    target="_blank"
>
    <img src="{{asset('assets/img/price_tag_ico.png')}}">
</a>

@foreach($item->discount_values ?? collect() as $value)
    <div class="js-action-string" style="color: red;">
        от {{$value->condition}} шт {{price(percent($item->price_rub, $value->value))}} руб
    </div>
@endforeach
