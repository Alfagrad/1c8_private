@php 
    global $i;
    $GLOBALS['i'] = 0;
    $is_analog_line = 0; // метка для вывода аналогов

        $snippet = "service.includes.item_block_line";

@endphp

<div class="product-block">
    <h3>Запчасти</h3>

    <!-- tut budut ssylki -->
    @if($scheme->count())
        <ul class="list-unstyled spares-tabs-inner">
            @foreach($scheme as $key => $sch)
                @if($key == 0)
                    <li class="active js-spare-tab"><a href="#tab-all">Все запчасти</a></li>
                    <li class="js-spare-tab">
                        <a href="#tab-{{ $sch->scheme_no }}">

                            @if($sch->scheme_name)

                            {{ $sch->scheme_name }}

                            @else

                            Схема №{{ $sch->scheme_no }}

                            @endif

                        </a>
                    </li>
                    @continue
                @endif
                <li class="js-spare-tab">
                    <a href="#tab-{{ $sch->scheme_no }}">

                        @if($sch->scheme_name)

                        {{ $sch->scheme_name }}

                        @else

                        Схема №{{ $sch->scheme_no }}

                        @endif

                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content">
            @foreach($scheme as $key => $sch)
                @if($key == 0)
                    <div class="tab-pane fade in active" id="tab-all">
                        <!-- Вывод всех запчастей -->
                        <div class="product-block-content items-table">
                            <div class="    product-block-spares">
                                <div class="table-head">
                                    <div class="s-main-navigation">
                                        <div class="wrapper w-main-navigation">
                                            <div class="wrapper w-filters-table">

                                                <div class="input p-catalog">
                                                    <input type="text" class="thin js-filter-spare" placeholder="Фильтр по слову" name="filter_word" value="">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
 
                                @foreach($items as $item)
                                    @php $scheme_parts = $item; $item = $item->getItem; @endphp
                                    @if($item->count > 0)
                                        @php $items_yes = true; @endphp
                                        @break
                                    @endif
                                @endforeach
                                @if($items_yes)
                                    <div class="table-wrapper">
                                        <div class="table-head">
                                            <h5>В наличии</h5>
                                            <table>
                                                <thead>
                                                <tr>
                                                    <td class="td-image"></td>
                                                    <td class="td-article">Код</td>
                                                    <td class="td-article">Номер схемы</td>
                                                    <td class="td-article">Номер в схеме</td>
                                                    <td class="td-name">Наименование</td>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="table-body">
                                            <table id="tb">
                                                <tbody>

                                                @foreach($items->sortBy('number_in_schema') as $item)
                                                    @php $scheme_parts = $item; $item = $item->getItem; @endphp
                                                    @if($item->count > 0)

@include($snippet)

                                                    @endif
                                                @endforeach

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>
                                @endif

                                @foreach($items as $item)
                                    @php $scheme_parts = $item; $item = $item->getItem; @endphp
                                    @if($item->count == 0)
                                        @php $items_no = true; @endphp
                                        @break
                                    @endif
                                @endforeach
                                @if($items_no)
                                    <div class="table-wrapper">
                                        <div class="table-head">
                                            <br/>
                                            <h5>Нет в наличии</h5>
                                            <table>
                                                <thead>
                                                <tr>
                                                    <td class="td-image"></td>
                                                    <td class="td-article">Код</td>
                                                    <td class="td-article">Номер схемы</td>
                                                    <td class="td-article">Номер в схеме</td>
                                                    <td class="td-name">Наименование</td>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="table-body">
                                            <table>
                                                <tbody>
                                                @foreach($items->sortBy('number_in_schema') as $item)
                                                    @php $scheme_parts = $item; $item = $item->getItem; @endphp
                                                    @if($item->count <= 0)

@include($snippet)

                                                    @endif
                                                @endforeach

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    <!-- Вывод запчастей для первой схемы -->
                    <div class="tab-pane fade in" id="tab-{{ $sch->scheme_no }}">
                        <div class="product-block-sheme active">
                            <div class="product-block-sheme__header">
                                <button class="product-block-sheme__toggler js-collapse-toggle" type="button">
                                    <span>Показать схему</span>
                                    <span>Скрыть схему</span>
                                </button>
                            </div>
                            <div class="product-block-sheme__body collapse in">
                                <div class="product-block-sheme__img">
                                    <img  
                                         src="{{ asset('storage/item-images/'.$sch->scheme_image) }}"
                                         alt="Схема">
                                </div>
                            </div>
                        </div>
                        <!-- Вывод запчастей -->
                        <div class="product-block-content items-table">
                            <div class="product-block-spares">
                                <div class="table-head">
                                    <div class="s-main-navigation">
                                        <div class="wrapper w-main-navigation">
                                            <div class="wrapper w-filters-table">

                                                <div class="input p-catalog">
                                                    <input type="text" class="thin js-filter-spare-2" placeholder="Фильтр по слову" name="filter_word" value="">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @foreach($items->where('scheme_no', $sch->scheme_no) as $item)
                                    @php $scheme_parts = $item; $item = $item->getItem; @endphp
                                    @if($item->count > 0)
                                        @php $items_yes = true; @endphp
                                        @break
                                    @endif
                                @endforeach
                                @if($items_yes)
                                    <div class="table-wrapper">
                                        <div class="table-head">
                                            <h5>В наличии</h5>
                                            <table>
                                                <thead>
                                                <tr>
                                                    <td class="td-image"></td>
                                                    <td class="td-article">Код</td>
                                                    <td class="td-article">Номер схемы</td>
                                                    <td class="td-article">Номер в схеме</td>
                                                    <td class="td-name">Наименование</td>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="table-body">
                                            <table>
                                                <tbody>
                                                @foreach($items->where('scheme_no', $sch->scheme_no)->sortBy('number_in_schema') as $item)
                                                    @php $scheme_parts = $item; $item = $item->getItem; @endphp
                                                    @if($item->count > 0)

@include($snippet)

                                                    @endif
                                                @endforeach

                                                </tbody>
                                            </table>

                                        </div>

                                    </div>
                                @endif

                                @foreach($items->where('scheme_no', $sch->scheme_no) as $item)
                                    @php $scheme_parts = $item; $item = $item->getItem; @endphp
                                    @if($item->count == 0)
                                        @php $items_no = true; @endphp
                                        @break
                                    @endif
                                @endforeach
                                @if($items_no)
                                    <div class="table-wrapper">
                                        <div class="table-head">
                                            <br/>
                                            <h5>Нет в наличии</h5>
                                            <table>
                                                <thead>
                                                <tr>
                                                    <td class="td-image"></td>
                                                    <td class="td-article">Код</td>
                                                    <td class="td-article">Номер схемы</td>
                                                    <td class="td-article">Номер в схеме</td>
                                                    <td class="td-name">Наименование</td>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="table-body">
                                            <table>
                                                <tbody>
                                                @foreach($items->where('scheme_no', $sch->scheme_no)->sortBy('number_in_schema') as $item)
                                                    @php $scheme_parts = $item; $item = $item->getItem; @endphp
                                                    @if($item->count <= 0)

@include($snippet)

                                                    @endif
                                                @endforeach

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    @continue
                @endif
                <div class="tab-pane fade" id="tab-{{ $sch->scheme_no }}">
                    <!-- content taba -->
                    <div class="product-block-sheme active">
                        <div class="product-block-sheme__header">
                            <button class="product-block-sheme__toggler js-collapse-toggle" type="button">
                                <span>Показать схему</span>
                                <span>Скрыть схему</span>
                            </button>
                        </div>
                        <div class="product-block-sheme__body collapse in">
                            <div class="product-block-sheme__img">
                                <img  
                                     src="{{ asset('storage/item-images/'.$sch->scheme_image) }}"
                                     alt="Схема">
                            </div>
                        </div>
                    </div>

                    <!-- Вывод запчастей -->
                    <div class="product-block-content items-table">
                        <div class="product-block-spares">
                            <div class="table-head">
                                <div class="s-main-navigation">
                                    <div class="wrapper w-main-navigation">
                                        <div class="wrapper w-filters-table">

                                            <div class="input p-catalog">
                                                <input type="text" class="thin js-filter-spare-3" placeholder="Фильтр по слову" name="filter_word" value="">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            @foreach($items->where('scheme_no', $sch->scheme_no) as $item)
                                @php $scheme_parts = $item; $item = $item->getItem; @endphp
                                @if($item->count > 0)
                                    @php $items_yes = true; @endphp
                                    @break
                                @endif
                            @endforeach
                            @if($items_yes)
                                <div class="table-wrapper">
                                    <div class="table-head">
                                        <h5>В наличии</h5>
                                        <table>
                                            <thead>
                                            <tr>
                                                <td class="td-image"></td>
                                                <td class="td-article">Код</td>
                                                <td class="td-article">Номер схемы</td>
                                                <td class="td-article">Номер в схеме</td>
                                                <td class="td-name">Наименование</td>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="table-body">
                                        <table>
                                            <tbody>
                                            @foreach($items->where('scheme_no', $sch->scheme_no)->sortBy('number_in_schema') as $item)
                                                @php $scheme_parts = $item; $item = $item->getItem; @endphp
                                                @if($item->count > 0)

@include($snippet)

                                                @endif
                                            @endforeach

                                            </tbody>
                                        </table>

                                    </div>

                                </div>
                            @endif

                            @foreach($items->where('scheme_no', $sch->scheme_no) as $item)
                                @php $scheme_parts = $item; $item = $item->getItem; @endphp
                                @if($item->count == 0)
                                    @php $items_no = true; @endphp
                                    @break
                                @endif
                            @endforeach
                            @if($items_no)
                                <div class="table-wrapper">
                                    <div class="table-head">
                                        <br/>
                                        <h5>Нет в наличии</h5>
                                        <table>
                                            <thead>
                                            <tr>
                                                <td class="td-image"></td>
                                                <td class="td-article">Код</td>
                                                <td class="td-article">Номер схемы</td>
                                                <td class="td-article">Номер в схеме</td>
                                                <td class="td-name">Наименование</td>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="table-body">
                                        <table>
                                            <tbody>
                                            @foreach($items->where('scheme_no', $sch->scheme_no)->sortBy('number_in_schema') as $item)
                                                @php $scheme_parts = $item; $item = $item->getItem; @endphp
                                                @if($item->count <= 0)

@include($snippet)

                                                @endif
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    @endif




</div>



@verbatim
    <script>
        $(".spares-tabs-inner a").on("click", function(e) {
            console.log("работает");
            e.preventDefault();
            var link = $(this);
            var tab = $(this).attr("href");
            $(".spares-tabs-inner li").removeClass("active");
            link.parent("li").addClass("active");
            $(".product-block .tab-pane").removeClass("in active");
            $(tab).addClass("in active");
        });
    </script>
@endverbatim

<script type="text/javascript">
    // фильтр по слову

    // при нажатии на табы обнуляем введенные в ранее значения 
    $('.js-spare-tab').on('click', function() {
        $('.js-filter-spare, .js-filter-spare-2, .js-filter-spare-3').val('');            
        $('a.name').parent().parent().parent().css('display', 'table-row');
    });

    // фильтруем
    $('.js-filter-spare, .js-filter-spare-2, .js-filter-spare-3').on('change keyup input click', function(e) {
        e.preventDefault();

        var filter_word = $(this).val();
        filter_word = filter_word.toLowerCase();

        $(function() {
            var ddd = $('a.name');
            var txt;
            $.each(ddd, function(i, v) {
                txt = v.text.toLowerCase();
                if (txt.indexOf(filter_word) == -1) {
                    $(this).parent().parent().parent().css('display', 'none');
                } else {
                    $(this).parent().parent().parent().css('display', 'table-row');
                }
            });
        });
    });

</script>



