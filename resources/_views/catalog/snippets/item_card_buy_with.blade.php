<div class="item-page_item-lines-block">

    <div class="item-page_filters about">

        @include('catalog.snippets.filters_line')  
        
    </div>

    <div class="header">С этим товаром покупают</div>

    @php($cat_index = 1)

    @if($buy_with_cat->count())

        @foreach($buy_with_cat as $cat)

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



    @if($buy_with->count())

        @php($line_count = $buy_with_count)

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

            @foreach($buy_with as $item)

                @php($item->not_analog = true)

                @include('catalog.snippets.item_lines', [
                        'line_four' => true,
                        'line_count' => $line_count,
                        'cat_index' => $cat_index,
                    ])

            @endforeach

        </div>

    @endif

</div>
