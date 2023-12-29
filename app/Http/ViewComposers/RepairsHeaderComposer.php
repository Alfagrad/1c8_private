<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

use App\Models\ProfileRepair;


class RepairsHeaderComposer
{
    public function compose(View $view) {

        if(\Auth::check()) {

            $repairs = ProfileRepair::where('profile_unp', \Auth::user()->profile->unp)->get();

            $data['repairsCountInWork'] = $repairs->where('state', 1)->count();
            $data['repairsCountReady'] = $repairs->where('state', 2)->count();

            return $view->with($data);
        }
    }
}
