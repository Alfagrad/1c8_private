@props(['title' => '123'])

<div class="hidden fixed top-0 left-0 w-screen h-screen bg-black bg-opacity-50 flex justify-center items-center">

    <div class="">

        <div class="flex justify-between">
            <div class="">{{$title}}</div>
            <div wire:click="modalClose" class="" title="Закрыть">Х</div>
        </div>

        <div class="">
            {{$slot}}
        </div>

    </div>

</div>
