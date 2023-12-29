<?php

namespace App\Http\Controllers\Open;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class Brands extends Controller
{

    public function __invoke(): View
    {
        return view('open.brand');
    }

}
