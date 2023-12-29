<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class CarrencyRateComposer
{
    public function compose(View $view) {

        // курсы валют
        $data['usd_opt'] = setting('header_usd');
        $data['usd_mr'] = setting('header_usd_mrc');

        return $view->with($data);
    }
}
