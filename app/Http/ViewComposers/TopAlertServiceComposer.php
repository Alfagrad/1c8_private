<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Page;

class TopAlertServiceComposer
{
    public function compose(View $view) {

        $top_alert_service = Page::find(26);

        return $view->with('top_alert_service', $top_alert_service);
    }
}
