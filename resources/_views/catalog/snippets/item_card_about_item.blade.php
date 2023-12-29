<div class="item-page_item-description-block">

    <div class="description-element parameters">

        <div class="header">Характеристики</div>
        <div class="text">
            @foreach($item_card->charValues as $value)
                <div class="parameter-element">
                    <strong>{{ $value->charName->name }}:</strong>
                    {{ $value->value }}
                </div>
            @endforeach
        </div>

    </div>

    <div class="description-element brief">

        <div class="header">Краткое описание</div>
        <div class="text">
            @foreach($more_about as $str)
                @if(trim($str))
                    <p>{{ $str }}</p>
                @else
                    <br>
                @endif
            @endforeach
        </div>

    </div>

    <div class="description-element advantages">

        <div class="header">Преимущества</div>
        <div class="text">
            @foreach($advantages as $str)
                @if(trim($str))
                    <p>{{ $str }}</p>
                @else
                    <br>
                @endif
            @endforeach
        </div>

    </div>

    <div class="description-element more-parameters">

        <div class="header">Дополнительные данные по товару и продавцу</div>
        <div class="text">
            @if($item_card->brand)
                <div class="parameter-element">
                    <strong>Бренд:</strong>
                    {{ $item_card->getRelation('brand')->name }}
                </div>
            @endif

            @if($item_card->factory)
                <div class="parameter-element">
                    <strong>Производитель:</strong>
                    {!! $item_card->factory !!}
                </div>
            @endif

            @if($item_card->apply)
                <div class="parameter-element">
                    <strong>Назначение:</strong>
                    {!!  $item_card->apply !!}
                </div>
            @endif

            @if($item_card->shelf_life)
                <div class="parameter-element">
                    <strong>Срок службы:</strong>
                    {!!  $item_card->shelf_life !!}
                </div>
            @endif

            @if($item_card->country)
                <div class="parameter-element">
                    <strong>Страна изготовления:</strong>
                    {!!  $item_card->country !!}
                </div>
            @endif

            @if($item_card->barcode)
                <div class="parameter-element">
                    <strong>Штрих-код:</strong>
                    {!! $item_card->barcode !!}
                </div>
            @endif

            @if($item_card->certificate)
                <div class="parameter-element">
                    <strong>Сертификат:</strong>
                    {!! $item_card->certificate ? : 'не указано' !!}
                </div>
            @endif

            @if($item_card->depth && $item_card->width && $item_card->height)
                <div class="parameter-element">
                    <strong>Габариты упаковки:</strong>
                    {!! $item_card->depth !!}х{!! $item_card->width !!}х{!! $item_card->height !!} мм.
                </div>
            @endif

            @if($item_card->weight)
                <div class="parameter-element">
                    <strong>Вес с упаковкой:</strong>
                    {!! $item_card->weight !!} кг.
                </div>
            @endif

            @if($item_card->guarantee_period)

                <div class="parameter-element">
                    <strong>Гарантийный срок:</strong>
                    {{ $item_card->guarantee_period }}
                    мес.
                </div>

            @endif

        </div>

    </div>

</div>

<div class="item-page_item-lines-block">

{{--     @if($discountedItem->count() || $buyForget->count())

        <div class="item-page_filters about">

            @include('catalog.snippets.filters_line')

        </div>

    @endif --}}

{{--     @if($discountedItem->count())

        @php($line_count = 0)
        @php($cat_index = 100)
        @foreach($discountedItem as $buy)
            @if($buy->count > 0)
                @php($line_count++)
            @endif
        @endforeach

        <div class="header">Уцененные</div>

        <div class="catalog-lines-section_catalog-name-block-wrapper">

            <div class="catalog-lines-section_catalog-name-block">

                <div class="catalog-lines-section_toggler-wrapper">

                    @if($line_count > 4)

                        <div
                            class="catalog-lines-section_toggler js-cat-four-toggler"
                            title="Скрыть-показать уцененные товары"
                            data-cat_index = "{{ $cat_index }}"
                        >+</div>

                    @endif

                </div>

                <div class="catalog-lines-section_catalog-name">

                    @if($line_count > 4)

                        <span class="item-page_view-all js-cat-{{ $cat_index }}-title">Показать все ({{ $line_count }})</span>
                        <span class="item-page_view-all js-cat-{{ $cat_index }}-title" style="display: none;">Скрыть</span>

                    @endif

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

        <div class="items-line-block js-four-lines-block">

            @foreach($discountedItem as $item)

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

    @endif --}}

{{--     @if($buyForget->count())

        @php($line_count = 0)
        @php($cat_index = 101)
        @foreach($buyForget as $buy)
            @if($buy->count > 0)
                @php($line_count++)
            @endif
        @endforeach

        <div class="header">Не забудь купить</div>

        <div class="catalog-lines-section_catalog-name-block-wrapper">

            <div class="catalog-lines-section_catalog-name-block">

                <div class="catalog-lines-section_toggler-wrapper">

                    @if($line_count > 4)

                        <div
                            class="catalog-lines-section_toggler js-cat-four-toggler"
                            title="Скрыть-показать товары"
                            data-cat_index = "{{ $cat_index }}"
                        >+</div>

                    @endif

                </div>

                <div class="catalog-lines-section_catalog-name">

                    @if($line_count > 4)

                        <span class="item-page_view-all js-cat-{{ $cat_index }}-title">Показать все ({{ $line_count }})</span>
                        <span class="item-page_view-all js-cat-{{ $cat_index }}-title" style="display: none;">Скрыть</span>

                    @endif

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

        <div class="items-line-block js-four-lines-block">

            @foreach($buyForget as $item)

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

    @endif --}}

</div>
