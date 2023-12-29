<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Page;

class TopAlertComposer
{
    public function compose(View $view) {

        $top_alert = Page::find(22);

        return $view->with('top_alert', $top_alert);
    }
}
