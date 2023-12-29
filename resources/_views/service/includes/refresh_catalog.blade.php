@php 
    global $i;
    $GLOBALS['i'] = 0;
    $is_analog_line = 0; // метка для вывода аналогов
@endphp

<div>
    @foreach($categories as $cat)
        @if($cat->items->count() > 0)

            <div class="w-table _toggled">

                <div class="table-head">
                    <table>
                        <thead>
                        <tr>
                            <td class="td-image">
                                <div class="toggler-button"></div>
                            </td>

                            <td class="td-article">
                            </td>
                            <td class="td-name">{{$cat->name}}</td>
                            <td class="td-sale"></td>
                            <td class="td-pcs"></td>
                            <td class="td-valible"></td>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="table-body _toggled">
                    <table>
                        <tbody>

                        @foreach($cat->items as $item)
                            @if($item->count > 0)

@include('service.includes.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('service.includes.tr_partial_analog')


                            @endif
                        @endforeach

                        @foreach($cat->items as $item)
                            @if($item->count <= 0)

@include('service.includes.item_block_line')
{{-- Выводим аналоги для товаров, которые являются и товаром и запчастью --}}
@include('service.includes.tr_partial_analog')

                            @endif
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

        @endif
    @endforeach

</div>

{{-- Переключатели для аналогов и уценки --}}
<script type="text/javascript" src="{{ asset('assets/js/togglers.js') }}"></script>
