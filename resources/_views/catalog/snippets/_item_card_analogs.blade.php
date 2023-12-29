<div class="item-page_item-lines-block">

    <div class="item-page_filters about">

        @include('catalog.snippets.filters_line')  
        
    </div>

    <div class="header">Аналоги</div>

    <div class="catalog-lines-section_catalog-name-block-wrapper js-cat-header">

        <div class="catalog-lines-section_catalog-name-block">

            <div class="catalog-lines-section_toggler-wrapper">

                <div
                    class="catalog-lines-section_toggler js-catalog-toggler"
                    title="Скрыть-показать аналоги"
                    data-cat_index="analogs"
                >
                    -
                </div>

            </div>

            <div class="catalog-lines-section_catalog-name">
                Наименование
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

    <div class="items-line-block js-lines-block js-index-analogs">

        @foreach($analogs as $item)

            @include('catalog.snippets.new_item_line')

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

</div>