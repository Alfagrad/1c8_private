<?php

namespace App\Http\Controllers\Open;

use App\Http\Controllers\Controller;

class Dealer extends Controller
{
    public function __invoke()
    {

        return view('open.dealer');
    }
}
