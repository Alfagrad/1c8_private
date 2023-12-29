@extends('item.line.item', ['class' => ''])

@section('price')

    <x-item.opt_price :$item></x-item.opt_price>
    <x-item.markup :$item></x-item.markup>
    <x-item.mr_price :$item></x-item.mr_price>

@overwrite

@section('count')
    <div class="catalog-item-line_input-block-wrapper">
        @if ($item->amount > 0)
            <livewire:count :cart="$carts->get($item->id_1c)" :itemId1c="$item->id_1c" :key="$item->uuid"/>
        @endif
    </div>
@overwrite

