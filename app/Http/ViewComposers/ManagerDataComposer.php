<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

// use App\Models\ProfileDebt;

class ManagerDataComposer
{
    public function compose(View $view) {

        if(\Auth::check()) {

            // смотрим, есть ли партнер
            $partner = \Auth::user()->profile->partner;

            // если есть
            if ($partner) {
                // берем менеджера
                $manager = $partner->manager()->first();
            } else {
                $manager = null;
            }

            $data['manager'] = $manager;

            return $view->with($data);
        }
    }
}
