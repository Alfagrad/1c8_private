@if ($paginator->lastPage() > 1)

    <div class="pagination">

        @if(!($paginator->currentPage() == 1))

            <a href="{{ $paginator->url($paginator->currentPage() - 1) }}" class="left">
                @include('svg.arrow')
            </a>

        @endif

        @for ($i = 1; $i <= $paginator->lastPage(); $i++)

            <a
                class="link {{ ($paginator->currentPage() == $i) ? 'active' : '' }}"
                href="@if($i == 1){{ URL::current() }}@else{{ $paginator->url($i) }}@endif"
            >
                {{ $i }}
            </a>

        @endfor

        @if(!($paginator->currentPage() == $paginator->lastPage()))

            <a href="{{ $paginator->url($paginator->currentPage() + 1) }}" class="right">
                @include('svg.arrow')
            </a>

        @endif

    </div>

@endif
