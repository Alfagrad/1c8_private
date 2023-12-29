@extends('item.line.item', compact('class'))

@section('count')
    <div class="catalog-item-line_input-block-wrapper">
        <livewire:count :cart="$orderItem->cart" :key="$orderItem->cart->id"/>
    </div>
@overwrite

@section('weight')
    <x-item.weight :$item :count="$orderItem->item_count"></x-item.weight>
@overwrite

@section('delete')
    <div wire:click="delete('{{$orderItem->cart->id}}')" class="del-item">
        <span class="">+</span>
    </div>
@overwrite
