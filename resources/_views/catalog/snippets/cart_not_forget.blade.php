<div class="item-page_item-lines-block">

    <div class="item-page_filters about">

        @include('catalog.snippets.filters_line')  
        
    </div>


    <div class="header">Не забудь купить</div>

    @php($cat_index = 1)

    @if($buy_forget_cat->count())

        @foreach($buy_forget_cat as $cat)

            @php($line_count = 0)
            @foreach($cat['items'] as $buy)
                @if($buy->amount > 0)
                    @php($line_count++)
                @endif
            @endforeach

            <div class="catalog-lines-section_catalog-name-block-wrapper">

                <div class="catalog-lines-section_catalog-name-block">

                    <div class="catalog-lines-section_toggler-wrapper">

                        @if($line_count > 4)

                            <div
                                class="catalog-lines-section_toggler js-cat-four-toggler"
                                data-cat_index = "{{ $cat_index }}"
                                title="Скрыть-показать товары категории - {{ $cat['name'] }}"
                            >+</div>

                        @endif

                    </div>

                    <div class="catalog-lines-section_catalog-name">

                        @if($line_count > 4)

                            <span class="item-page_view-all js-cat-{{ $cat_index }}-title">Показать все ({{ $line_count }})</span>
                            <span class="item-page_view-all js-cat-{{ $cat_index }}-title" style="display: none;">Скрыть</span>

                        @endif

                        {{ $cat['name'] }}
                    </div>

                    <div class="catalog-lines-section_catalog-name-price js-opt-head" style="display: {{ $opt_style }};">
                        Цена BYN с НДС
                    </div>

                    <div class="catalog-lines-section_catalog-name-percent js-purcent-head" style="display: {{ $purcent_style }};">
                        Наценка дилера, %
                    </div>

                    <div class="catalog-lines-section_catalog-name-mr js-mr-head" style="display: {{ $mr_style }};">
                        МРЦ BYN
                    </div>

                    <div class="catalog-lines-section_catalog-name-end"></div>

                </div>
                
            </div>

            <div class="items-line-block js-four-lines-block">

                @foreach($cat['items'] as $item)

                    @include('catalog.snippets.item_lines', [
                            'line_four' => true,
                            'line_count' => $line_count,
                            'cat_index' => $cat_index,
                        ])

                @endforeach

            </div>

            @php($cat_index++)

        @endforeach

    @endif

    @if($buy_forget->count())

        @php($line_count = $buy_forget->count())

        <div class="catalog-lines-section_catalog-name-block-wrapper">

            <div class="catalog-lines-section_catalog-name-block">

                <div class="catalog-lines-section_toggler-wrapper">

                    @if($line_count > 4)

                        <div
                            class="catalog-lines-section_toggler js-cat-four-toggler"
                            data-cat_index = "{{ $cat_index }}"
                            title="Скрыть-показать товары"
                        >+</div>

                    @endif

                </div>

                <div class="catalog-lines-section_catalog-name">

                    @if($line_count > 4)

                        <span class="item-page_view-all js-cat-{{ $cat_index }}-title">Показать все ({{ $line_count }})</span>
                        <span class="item-page_view-all js-cat-{{ $cat_index }}-title" style="display: none;">Скрыть</span>

                    @endif

                    Товар
                </div>

                <div class="catalog-lines-section_catalog-name-price js-opt-head" style="display: {{ $opt_style }};">
                    Цена BYN с НДС
                </div>

                <div class="catalog-lines-section_catalog-name-percent js-purcent-head" style="display: {{ $purcent_style }};">
                    Наценка дилера, %
                </div>

                <div class="catalog-lines-section_catalog-name-mr js-mr-head" style="display: {{ $mr_style }};">
                    МРЦ BYN
                </div>

                <div class="catalog-lines-section_catalog-name-end"></div>

            </div>
            
        </div>

        <div class="items-line-block">

            @foreach($buy_forget as $item)

                @php($item->not_analog = true)

                @include('catalog.snippets.item_lines', [
                        'line_four' => true,
                        'line_count' => $line_count,
                        'cat_index' => $cat_index,
                    ])

            @endforeach

        </div>

    @endif

    @if($actions->count())

        @php($line_count = 0)
        @php($cat_index = 101)
        @foreach($actions as $buy)
            @if($buy->count > 0)
                @php($line_count++)
            @endif
        @endforeach

        <div class="catalog-lines-section_catalog-name-block-wrapper">

            <div class="catalog-lines-section_catalog-name-block">

                <div class="catalog-lines-section_toggler-wrapper">

                    @if($line_count > 4)

                        <div
                            class="catalog-lines-section_toggler js-cat-four-toggler"
                            title="Скрыть-показать акционные товары"
                            data-cat_index = "{{ $cat_index }}"
                        >+</div>

                    @endif

                </div>

                <div class="catalog-lines-section_catalog-name">

                    @if($line_count > 4)

                        <span class="item-page_view-all js-cat-{{ $cat_index }}-title">Показать все ({{ $line_count }})</span>
                        <span class="item-page_view-all js-cat-{{ $cat_index }}-title" style="display: none;">Скрыть</span>

                    @endif

                    Акционные товары
                </div>

                <div class="catalog-lines-section_catalog-name-price js-opt-head" style="display: {{ $opt_style }};">
                    Цена BYN с НДС
                </div>

                <div class="catalog-lines-section_catalog-name-percent js-purcent-head" style="display: {{ $purcent_style }};">
                    Наценка дилера, %
                </div>

                <div class="catalog-lines-section_catalog-name-mr js-mr-head" style="display: {{ $mr_style }};">
                    МРЦ BYN
                </div>

                <div class="catalog-lines-section_catalog-name-end"></div>

            </div>
            
        </div>

        <div class="items-line-block js-four-lines-block">

            @foreach($actions as $item)

                @php($item->not_analog = true)

                @include('catalog.snippets.item_lines', [
                        'line_four' => true,
                        'line_count' => $line_count,
                        'cat_index' => $cat_index,
                    ])
            
            @endforeach

        </div>

    @endif

{{--     @if($itemsFromOrder->count())

        @php($line_count = 0)
        @php($cat_index = 102)
        @foreach($itemsFromOrder as $buy)
            @if($buy->count > 0)
                @php($line_count++)
            @endif
        @endforeach

        <div class="catalog-lines-section_catalog-name-block-wrapper">

            <div class="catalog-lines-section_catalog-name-block">

                <div class="catalog-lines-section_toggler-wrapper">

                    @if($line_count > 4)

                        <div
                            class="catalog-lines-section_toggler js-cat-four-toggler"
                            title="Скрыть-показать ранее купленные товары"
                            data-cat_index = "{{ $cat_index }}"
                        >+</div>

                    @endif

                </div>

                <div class="catalog-lines-section_catalog-name">

                    @if($line_count > 4)

                        <span class="item-page_view-all js-cat-{{ $cat_index }}-title">Показать все ({{ $line_count }})</span>
                        <span class="item-page_view-all js-cat-{{ $cat_index }}-title" style="display: none;">Скрыть</span>

                    @endif

                    Ранее купленные
                </div>

                <div class="catalog-lines-section_catalog-name-price js-opt-head" style="display: {{ $opt_style }};">
                    Цена BYN с НДС
                </div>

                <div class="catalog-lines-section_catalog-name-percent js-purcent-head" style="display: {{ $purcent_style }};">
                    Наценка дилера, %
                </div>

                <div class="catalog-lines-section_catalog-name-mr js-mr-head" style="display: {{ $mr_style }};">
                    МРЦ BYN
                </div>

                <div class="catalog-lines-section_catalog-name-end"></div>

            </div>
            
        </div>

        <div class="items-line-block js-four-lines-block">

            @foreach($itemsFromOrder as $item)

                @include('catalog.snippets.new_item_line', [
                        'line_four' => true,
                        'line_count' => $line_count,
                        'cat_index' => $cat_index,
                    ])

                @if($item->analog_list)
                    @php
                        // собираем аналоги в коллекцию
                        $analog_items = App\Item::whereIn('1c_id', explode(',', $item->analog_list))->get();
                        // берем количество единиц родителя
                        $parent_count = $item->count;
                        $parent_id = $item->{'1c_id'};
                    @endphp

                    @foreach($analog_items as $item)

                        @include('catalog.snippets.new_item_line', 
                            [
                                'analog_line' => true,
                                'parent_count' => $parent_count,
                                'parent_1c_id' => $parent_id,
                            ])

                    @endforeach
                @endif
            
            @endforeach

        </div>

    @endif
 --}}
</div>
