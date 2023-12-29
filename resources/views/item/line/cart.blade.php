@extends('item.line.item', compact('class'))

@section('price')

    <x-item.opt_price :$item></x-item.opt_price>
    <div class="purcent">
        <div>
            <div class="catalog-item-line_mobile-price-title">
                скидка
            </div>
            <span class="">{{ $orderItem->discount ?? 0 }}</span>%
        </div>
    </div>

    <div class="calculated-price">
        <div>
            <div class="catalog-item-line_mobile-price-title">
                цена расч
            </div>
            <div class="">{{ price($orderItem->item_price) }}</div>
        </div>
    </div>

@overwrite

@section('count')
    <div class="catalog-item-line_input-block-wrapper">
        <livewire:count :cart="$orderItem->cart" :key="$orderItem->cart->id"/>
    </div>
@overwrite

@section('weight')
    <x-item.weight :$item :count="$orderItem->item_count"></x-item.weight>
@overwrite

@section('sum')
    <div class="total-price">
        <div>
            <div class="catalog-item-line_mobile-price-title">
                итого
            </div>
            <div class="">
                {{price($orderItem->item_sum_price)}}
                @if($isLessMin)
                    <div class="text-error-dark text-xs">Сумма менее {{price(config('settings.min_cart_to_order'))}} руб.</div>
                @endif
            </div>
        </div>
    </div>

@overwrite

@section('delete')
    <div wire:click="delete('{{$orderItem->cart->id}}')" class="del-item">
        <span class="">+</span>
    </div>
@overwrite
