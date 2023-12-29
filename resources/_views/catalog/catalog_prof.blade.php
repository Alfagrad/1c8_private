@extends('layouts.prof')

@section('content')
<body>
<div class="b-wrapper p-catalog small">
    @include('catalog.partials.header')
    @include('catalog.partials.menu')

    <div class="empty-header"></div>
    <!--MAIN WRAPPER-->
    <section class="s-main-wrapper">
        <div class="container">
            <div class="wrapper">
                <div class="w-main-table">
                    <div class="left">
                        @include('catalog.partials.leftMenu')
                    </div>

                    <div class="right">
                        @include('catalog.partials.rightMenu')
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        var use_local_storage = {{$useLocalStorage?1:0}}
        var search_category = {{$searchCategory?$searchCategory:0}}

    </script>
    @include('general.popups')
</div>



@include('general.scripts')

</body>
@endsection