<div>

    @if(count($messages['error']) > 0 || count($messages['success']) > 0)
        <div x-data="{show: true}"
             x-show="show"
             x-cloak
             x-transition.duration.500ms
             x-init="setTimeout(() => show=false, 3000)"
             class="fixed z-40 right-0 top-4 flex flex-col space-y-4"
             wire:key="{{ time() }}"
        >
            @foreach($messages as $type => $typeMessages)

                @if(count($typeMessages) <= 0)
                    @continue
                @endif

                <div class="max-w-xl py-6 px-12 border border-r-0 rounded-l-lg flex space-x-16
                    {{$type == 'success' ? 'bg-green-100 border-green-300 text-green-700' : 'bg-red-100 border-red-300 text-red-700'}}
                ">
                    <ul>
                        @foreach($typeMessages as $message)
                            <li>{{$message}}</li>
                        @endforeach
                    </ul>
                    <div @click="show=false" class="cursor-pointer hover:text-green-900">Х</div>
                </div>

            @endforeach

        </div>
    @endif

</div>

