<div class="cabinet-menu">

    <a href="{{ route('profile.index') }}" class="menu-button @if( Route::currentRouteName() == 'profile.index') active @endif">Учетная запись</a>

    <a href="{{ route('profile.orders') }}" class="menu-button @if( Route::currentRouteName() == 'profile.orders') active @endif">Мои заказы</a>

    <a href="{{ route('profile.repairs') }}" class="menu-button @if( Route::currentRouteName() == 'profile.repairs') active @endif">Мои ремонты</a>

    <a href="{{ route('profile.subscribes') }}" class="menu-button @if( Route::currentRouteName() == 'profile.subscribes') active @endif">Настройки рассылок</a>

</div>
