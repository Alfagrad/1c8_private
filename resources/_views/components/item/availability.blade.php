@props(['item'])

<div class="catalog-item-line_availability-block">

    @if(!$is_service)

        @if($item->amount > 0)

            @if($item->amount > 10)

                <div class="icon-yes verylot" title="Доступно более 10шт">

                    <span class="in_stock"><br>В наличии</span>

                    @if(auth()->user()->role_id != '2')

                        <br><strong>{{ $item->amount }} шт.</strong>

                    @else

                        <br><strong>>10 шт.</strong>

                    @endif

                </div>

            @elseif($item->amount >= 5 and $item->amount <= 10 )

                <div class="icon-yes lot" title="на складе {{$item->amount}}шт">
                    <span class="in_stock"><br>В наличии</span>
                    <br><strong>{{ $item->amount }} шт.</strong>
                </div>

            @else

                <div class="icon-yes" title="на складе {{$item->amount}} шт">
                    <span class="in_stock"><br>В наличии</span>
                    <br><strong>{{ $item->amount }} шт.</strong>
                </div>

            @endif

        @elseif($item->reserve > 0)


            <div title="Звоните">
                Резерв

                @if(auth()->user()->role_id != '2')

                    <br><strong>{{ $item->reserve }} шт.</strong>

                @endif

            </div>

        @elseif($item->locked > 0)

            <div title="Звоните">
                Уточните наличие

                @if(auth()->user()->role_id != '2')

                    <br><strong>{{ $item->locked }} шт.</strong>

                @endif

            </div>

        @elseif($item->expected > 0)


            <div class="icon-reserved" title="Поступит {{ $item->expected_date }}">
{{--            <div class="icon-reserved" title="Поступит {{ $real_date }}">--}}
                Поступит
                <br>{{ $item->expected_date }}
{{--                <br>{{ $real_date }}--}}

                @if(auth()->user()->role_id != '2')

                    <br><strong>{{ $item->expected }} шт.</strong>

                @endif

            </div>


        @else

            <div title="Нет на складе">
                Нет
            </div>

        @endif

    @endif

</div>
