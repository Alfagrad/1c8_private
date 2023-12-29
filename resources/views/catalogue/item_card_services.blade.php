<div class="item-page_item-lines-block">

    <div class="header">Работы</div>

    @php($cat_index = 1)

    @if($services->count())

        {{-- @php($line_count = $buy_with_count) --}}

        <div class="catalog-lines-section_catalog-name-block-wrapper">

            <div class="catalog-lines-section_catalog-name-block">

                <div class="catalog-lines-section_toggler-wrapper">

{{--                     @if($line_count > 4)

                        <div
                            class="catalog-lines-section_toggler js-cat-four-toggler"
                            data-cat_index = "{{ $cat_index }}"
                            title="Скрыть-показать товары"
                        >+</div>

                    @endif
 --}}
                </div>

                <div class="catalog-lines-section_catalog-name">

{{--                     @if($line_count > 4)

                        <span class="item-page_view-all js-cat-{{ $cat_index }}-title">Показать все ({{ $line_count }})</span>
                        <span class="item-page_view-all js-cat-{{ $cat_index }}-title" style="display: none;">Скрыть</span>

                    @endif
 --}}
                    {{ $services[0]['name'] }}
                </div>

                <div class="catalog-lines-section_catalog-name-price">
                    Нормо-часов
                </div>

                <div class="catalog-lines-section_catalog-name-end" style="flex: 0 0 150px;"></div>

            </div>

        </div>

        <div class="items-line-block">

            @foreach($services[0]['items'] as $item)

                {{-- @php($item->not_analog = true) --}}

                @include('catalogue.item_lines', [
                        // 'line_four' => true,
                        // 'line_count' => $line_count,
                        // 'cat_index' => $cat_index,
                    ])

            @endforeach

        </div>

    @endif

</div>
