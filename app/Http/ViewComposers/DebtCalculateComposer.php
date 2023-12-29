<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

use App\Models\ProfileDebt;

class DebtCalculateComposer
{
    public function compose(View $view) {

        if(\Auth::check()) {
            // берем УНП профайла юзера
            $profile_unp = \Auth::user()->profile->unp;

            $dept_sum = ProfileDebt::where('unp', $profile_unp)->sum('sum');

            $data['dept_sum'] = $dept_sum;

            return $view->with($data);
        }

    }
}
