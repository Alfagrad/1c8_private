@props(['title', 'cartOrders', 'cartOrderId', 'method'])

<div class="relocate-block">

    <div class="title">
        {{$title}}
    </div>

    <div class="relocate-links-block">

        @foreach($cartOrders as $cartOrder)

            @if($cartOrderId)
                <div class="relocate-link"
                     wire:click="{{$method}}(null)">
                    Основная
                </div>
            @endif

            @if($cartOrder->id == $cartOrderId)
                @continue
            @else
                <div wire:click="{{$method}}('{{$cartOrder->id}}')" class="relocate-link">
                    {{ $cartOrder->name }}
                </div>
            @endif

        @endforeach

    </div>

</div>
