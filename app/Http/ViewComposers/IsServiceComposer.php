<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class IsServiceComposer
{
    public function compose(View $view) {

        if(\Auth::check()) {
            // определяем тип клиента
            $client_type = \Auth::user()->profile->role;

            // если сервис
            if ($client_type == 'Сервис') {
                $is_service = true;
            } else {
                $is_service = false;
            }

            $data['is_service'] = $is_service;

            return $view->with($data);
        }

    }
}