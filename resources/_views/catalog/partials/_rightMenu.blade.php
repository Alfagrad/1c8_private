<div class="wrapper white-bg-wrapper page-inset-frame">

    <div class="wrapper items-table catalog-table">

@include('general.top_alert_category')

        @if(isset($blockSearchCatalog))
            {!! $blockSearchCatalog !!}
        @else
            <p class="change_category"><i>Выберите интересующие вас категории</i></p>
        @endif

    </div>

</div>
