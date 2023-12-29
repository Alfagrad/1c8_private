@php
    $isCatalog = !empty($isCatalog) ? $isCatalog : false;
@endphp

<div class="{{$isCatalog?'catalog-promo-link-element':'promo-link-element'}}" style="{{$styles = !empty($styles) ? $styles : ''}}">

    <a href="{{ $route }}" title="{{$title}}" class="{{$isCatalog?'button':'promo-link-button'}}">

        <div class="{{$isCatalog?'ico':'promo-link-ico'}}">
            <img src="{{ asset('storage/'.$ico) }}">
        </div>

        <div class="{{$isCatalog?'title':'promo-link-title'}}">
            {{$title}}
        </div>

    </a>

    @if(isset($count))
        <div class="promo-link-count">
            ({{ $count }})
        </div>
    @endif

</div>