<div class="wrapper white-bg-wrapper page-inset-frame service">
    <div class="wrapper w-table-check-all" style="display: none">
        <div class="toggler-button _minus" ></div>Cвернуть все
    </div>
    <div class="wrapper w-table-uncheck-all" style="display: none">
        <div class="toggler-button _plus " ></div>Развернуть все
    </div>
    <div class="wrapper items-table catalog-table">
        @if(isset($blockSearchCatalog))
            {!! $blockSearchCatalog !!}
        @else
            <p class="change_category"><i>Выберите категорию</i></p>
        @endif
    </div>

</div>
