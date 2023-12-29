@php
    if(false && profile()->isService()) {
        // Если сервисный центр
        $route = "service-category";
        $item_route = "service-item-view";
        $snippet = "service.includes.item_search_block_line";
        $route_word = "service";
    } else {
        $route = "catalogue.index";
        $snippet = "catalogue.search.item_line";
        $item_route = "itemCard";
        $route_word = "catalogue";
    }
@endphp

@if($categories->count())

    @foreach($categories as $cat)

        <div class="row dropper category-name">

            <a href="{{ route($route, ['id' => $cat->id_1c]) }}" title="{{ $cat->name }}">

                @php $name = $cat->name; @endphp
                @foreach($searchKeywords as $keyWord)
                    @php $name = preg_replace('/(' . $keyWord . ')/ui', "<span class='light'>$1</span>", $name); @endphp
                @endforeach
                {!! $name !!}
            </a>

            <div class="arrow"></div>

        </div>

        <div class="inset category">

            <a href="{{ route($route, ['id' => $cat->id_1c]) }}" class="w-image">

                <img src="{{ asset($imageResize->resize($cat->image, 200, 200)) }}" alt="{{ $cat->name }}">
            </a>
            <div class="name">
                <a href="{{ route($route, ['id' => $cat->id_1c]) }}">{{ $cat->name }}</a>
            </div>

        </div>

    @endforeach

@endif

<div class="search-count-block">
    <a href="/{{ $route_word }}/search?type={{ $type }}&search_keywords={{ $searchKeyword }}">
        Найдено:
        товаров - {{ $itemCount }} {{ Lang::choice('позиция|позиции|позиций', $itemCount, array(), 'ru') }}

        @if($itemCode)

            ; по коду - {{ $itemCode->count() }} {{ Lang::choice('позиция|позиции|позиций', $itemCount, array(), 'ru') }}

        @endif

        @if($itemArticle)

            @if($itemArticle->count())

                ; по артикулу - {{ $item_article_count }} {{ Lang::choice('позиция|позиции|позиций', $item_article_count, array(), 'ru') }}

            @endif

        @endif

    </a>
</div>

@php
    $patterns = '/(' . implode('|', $searchKeywords) . ')/isu';
    $replace = "<span class='light'>$1</span>";
@endphp

{{-- <div class="search-count-block">
    <a href="/{{ $route_word }}/search?type={{$type}}&search_keywords={{$searchKeyword}}">Найдено товаров: {{$itemCount}} {{Lang::choice('позиция|позиции|позиций', $itemCount, array(), 'ru')}}</a>
</div>
 --}}
@if($items_yes->count())

    <div class="row dropper header"><h5 class="search-results-status-title">В наличии ({{ $items_yes_count }})</h5></div>

    @foreach($items_yes as $item)

        @include($snippet)

    @endforeach

@endif

@if($items_yes_low_cost && $items_yes_low_cost->count())

    <div class="row dropper header"><h5 class="search-results-status-title">Уцененные ({{ $items_yes_low_cost_count }})</h5></div>

    @foreach($items_yes_low_cost as $item)

        @include($snippet)

    @endforeach

@endif

@if($items_no->count())

    <div class="row dropper header"><h5 class="search-results-status-title">Нет в наличии ({{ $items_no_count }})</h5></div>

    @foreach($items_no as $item)

        @include($snippet)

    @endforeach

@endif

@if($archive->count() && $type == 'products')

    <div class="row dropper header"><h5 class="search-results-status-title">Архивные ({{ $archive_count }})</h5></div>

    @foreach($archive as $item)

        @include($snippet)

    @endforeach

@endif

@if($itemCode)

    <div class="row dropper header"><h5 class="search-results-status-title">Поиск по коду</h5></div>

    @foreach($itemCode as $item)

        @include($snippet)

    @endforeach

@endif

@if($itemArticle)

    @if($itemArticle->count())

        <div class="row dropper header"><h5 class="search-results-status-title">Поиск по артикулу ({{ $item_article_count }})</h5></div>

        @foreach($itemArticle as $item)

            @include($snippet)

        @endforeach

    @endif


@endif

