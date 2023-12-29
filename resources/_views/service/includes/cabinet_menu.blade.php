<div class="left">
    <div class="wrapper white-bg-wrapper b-cabinet-navigation">
        <div class="wrapper w-cabinet-navigation" style="padding: 5px 0 0 0;">
            <a href="{{route('profileIndex')}}" class="button _gray @if( Route::currentRouteName() ==  'profileIndex') _active @endif" style="width: 50%;">Учетная запись</a>
            <a href="{{route('profileOrders')}}" class="button _gray @if( Route::currentRouteName() ==  'profileOrders') _active @endif" style="width: 50%;">Мои заказы</a>
{{--             <a href="{{route('profileRepairs')}}" class="button _gray @if( Route::currentRouteName() ==  'profileRepairs') _active @endif">Мои ремонты</a>
            <a href="{{route('profileSubscribes')}}" class="button _gray @if( Route::currentRouteName() ==  'profileSubscribes') _active @endif">Настройки рассылок</a> --}}
        </div>
    </div>
</div>