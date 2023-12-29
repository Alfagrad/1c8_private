<?php

use TCG\Voyager\Facades\Voyager;

if (! function_exists('setting')) {
    function setting($key)
    {
        $setting = Voyager::setting($key);
//        if($key == 'footer_fax_phone'){
//            dd($setting);
//        }
        return !is_array($setting) ? $setting : array_values($setting)[0];
    }
}

if (! function_exists('price')) {
    function price(float $price): float
    {
        return number_format($price, 2, '.', '');
    }
}

if (! function_exists('percent')) {
    function percent(float $price, float $percent): float
    {
        return $price * (1 + $percent / 100);
    }
}

if (! function_exists('profile')) {
    function profile(bool $builder = false): \App\Models\Profile
    {
        $user = auth()->user();
        return $builder ? $user->profile() : $user->profile ?? new \App\Models\Profile();
    }
}

if (! function_exists('nowTime')) {
    function nowTime(string $format = 'H:i:s'): string
    {
        return now()->format($format);
    }
}

//if (! function_exists('isService')) {
//    function isService(): bool
//    {
//        return profile()->role == 'Сервис';
//    }
//}
