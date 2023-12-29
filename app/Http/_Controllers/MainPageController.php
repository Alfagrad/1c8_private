<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

use Barryvdh\Debugbar\Facades\Debugbar;

class MainPageController extends Controller
{
    public function index()
    {
        Debugbar::info('12');
        return view('open_main_page');
    }

}
