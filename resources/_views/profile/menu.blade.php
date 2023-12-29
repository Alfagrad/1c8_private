<div class="cabinet-menu">

    <a href="{{ asset('profile/index') }}" class="menu-button @if( Route::currentRouteName() == 'profileIndex') active @endif">Учетная запись</a>

    <a href="{{ asset('profile/orders') }}" class="menu-button @if( Route::currentRouteName() == 'profileOrders') active @endif">Мои заказы</a>

    <a href="{{ asset('profile/repairs') }}" class="menu-button @if( Route::currentRouteName() == 'profileRepairs') active @endif">Мои ремонты</a>

    <a href="{{ asset('profile/subscribes') }}" class="menu-button @if( Route::currentRouteName() == 'profileSubscribes') active @endif">Настройки рассылок</a>

</div>