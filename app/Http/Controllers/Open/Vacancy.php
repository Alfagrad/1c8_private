<?php

namespace App\Http\Controllers\Open;

use App\Http\Controllers\Controller;
use App\Models\Vacancy as VacancyModel;

class Vacancy extends Controller
{
    public function __invoke()
    {
        $vacancies = VacancyModel::where('is_active', 1)->orderBy('sort')->get();

        return view('open.vacancy', compact('vacancies'));
    }
}
