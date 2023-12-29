@include('general.promo-link-block', [
    'route' => route('catalogue.newYear'),
    'title' => 'НОВОГОДНЯЯ РАСПРОДАЖА',
    'ico' => 'promo/is_new_year_action_ico.png',
    'count' => $is_new_year_action_items_count = !empty($is_new_year_action_items_count) ? $is_new_year_action_items_count : null,
    'styles' => (!empty($isCatalog) && $isCatalog)?'flex-basis:100%;':''
])



@include('general.promo-link-block', [
    'route' => route('catalogue.season'),
    'title' => 'Зимние предложения',
    'ico' => 'snowflake.png',
    'count' => $season_items_count = !empty($season_items_count) ? $season_items_count : null
])

@include('general.promo-link-block', [
    'route' => route('catalogue.actions'),
    'title' => 'Акции и скидки',
    'ico' => 'all_actions_ico.png',
    'count' => $all_actions_count = !empty($all_actions_count) ? $all_actions_count : null
])

@include('general.promo-link-block', [
    'route' => route('catalogue.newItems'),
    'title' => 'Все новинки',
    'ico' => 'new_items_ico.png',
    'count' => $new_items_count = !empty($new_items_count) ? $new_items_count : null
])

@include('general.promo-link-block', [
    'route' => route('catalogue.discounted'),
    'title' => 'Уцененные товары',
    'ico' => 'discounted_items_ico.png',
    'count' => $discounted_items_count = !empty($discounted_items_count) ? $discounted_items_count : null
])

@include('general.promo-link-block', [
    'route' => route('catalogue.singlePower'),
    'title' => 'Инструмент с единым аккумулятором',
    'ico' => 'single_power_ico.png',
    'count' => $single_power_items_count = !empty($single_power_items_count) ? $single_power_items_count : null,
    'styles' => (!empty($isCatalog) && $isCatalog)?'flex-basis:100%;':''
])

