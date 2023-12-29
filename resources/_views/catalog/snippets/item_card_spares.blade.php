<div class="item-page_spares-block">

    {{-- <div class="header">Запчасти</div> --}}

    <div class="spare-tabs-block">

{{--         <div class="spare-tab-element active js-spare-tab js-all-spares">
            Все запчасти
        </div>
 --}}
        @foreach($schemes as $sch)

            @php
                if($loop->iteration == 1) {
                    // id первой схемы
                    $first_scheme_id = $sch->id;
                }
            @endphp

            <div
                class="spare-tab-element js-spare-tab @if($loop->iteration == 1){{'active'}}@endif"
                data-num-scheme="{{ $loop->iteration }}"
                data-scheme_id="{{ $sch->id }}"
            >

                {{ $sch->name }}

            </div>

        @endforeach
        
    </div>

    @foreach($schemes as $sch)

        @php
            if($loop->iteration == 1) {
                $schema_img_style = "block";
            } else {
                $schema_img_style = "none";
            }
        @endphp

        <div class="scheme-img-block js-scheme js-scheme-{{ $loop->iteration }}" style="display: {{ $schema_img_style }};">

            <div class="scheme-img-toggler js-img-view-toggler">
                <span class="js-but-name">Скрыть схему</span>
                @include('svg.phones_arrow_ico')
            </div>

            <div class="view-img">
                <img src="{{ asset('storage/ut_1c8/scheme-images/'.$sch->image) }}" title="Схема {{ $sch->name }}" alt="Схема {{ $sch->name }}">
            </div>

        </div>

    @endforeach


    <div class="item-page_filters spares">

        @include('catalog.snippets.filters_line')  
        
    </div>

    {{-- <div class="title">В наличии</div> --}}

    <div class="catalog-lines-section_catalog-name-block-wrapper">

        <div class="catalog-lines-section_catalog-name-block">

{{--             <div class="catalog-lines-section_toggler-wrapper">

                <div
                    class="catalog-lines-section_toggler js-catalog-toggler"
                    data-cat_index = "in_stock"
                    title="Скрыть-показать отсутствующие запчасти"
                >
                    -
                </div>

            </div> --}}

            <div class="catalog-lines-section_catalog-name spares">
                <div class="code-title">
                    Код
                </div>
                <div class="spare-name-title">
                    Имя в схеме
                </div>
                <div class="num-in-scheme-title">
                    &#8470; в схеме
                </div>
                <div class="num-in-scheme-title">
                    Кол в схеме
                </div>
                <div class="item-name">
                    Наименование
                </div>
            </div>

            @if(!$is_service)

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

            @endif

        </div>
        
    </div>

    @foreach($schemes as $sch)

        @php
            if($loop->iteration == 1) {
                $schema_items_style = "block";
            } else {
                $schema_items_style = "none";
            }
        @endphp

        <div class="items-line-block js-spares-block js-scheme-{{ $sch->id }}" style="display: {{ $schema_items_style }};">

            @foreach($spares->where('scheme_id', $sch->id) as $spare)

                @if($spare->item)

                    @php
                        $item = $spare->item;
                        // метка, что это запчасть
                        $item->is_spare = true;
                    @endphp

                    @include('catalog.snippets.item_lines')

                @else

                    @include('catalog.snippets.spare_empty_line')

                @endif

            @endforeach

        </div>

    @endforeach

{{--
    <div class="title">Нет в наличии</div>

    <div class="catalog-lines-section_catalog-name-block-wrapper">

        <div class="catalog-lines-section_catalog-name-block">

            <div class="catalog-lines-section_toggler-wrapper">

                <div
                    class="catalog-lines-section_toggler js-catalog-toggler"
                    data-cat_index = "out_stock"
                    title="Скрыть-показать"
                >
                    -
                </div>

            </div>

            <div class="catalog-lines-section_catalog-name spares">
                <div class="code-title">
                    Код
                </div>
                <div class="scheme-num-title">
                    Номер схемы
                </div>
                <div class="num-in-scheme-title">
                    Номер в схеме
                </div>
                <div class="item-name">
                    Наименование
                </div>
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

    <div class="items-line-block js-index-out_stock">

        @foreach($spares as $spare)
            @php($item = $spare->getItem)

            @if($item->count <= 0)

                @include('catalog.snippets.new_item_line', [
                        'scheme_item' => true,
                        'scheme_no' => $spare->scheme_no,
                        'number_in_schema' => $spare->number_in_schema
                    ])

                @endif

        @endforeach

    </div>
--}}


    
</div>
