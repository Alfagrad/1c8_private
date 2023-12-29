@if($items->count())
    @foreach($items as $item)

        <div wire:click="setItem({{$item->id_1c}}, '{{$item->name}}')" class="item-serch_result-item" data-id="{{ $item->{'1c_id'} }}">{{ $item->name }}</div>

    @endforeach
@else

<div class="item-serch_result-false">Ничего не найдено!</div>

@endif
