<?php

namespace App\Http\Controllers\Open;

use App\Http\Controllers\Controller;

class Index extends Controller
{
    public function __invoke()
    {
        return view('open.index');
    }

}
