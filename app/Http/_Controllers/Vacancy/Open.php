<?php

namespace App\Http\Controllers\Vacancy;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;

class Open extends Controller
{
    public function __invoke()
    {
        $vacancies = Vacancy::where('is_active', 1)->orderBy('sort')->get();

        return view('vacancy.open', compact('vacancies'));
    }
}
