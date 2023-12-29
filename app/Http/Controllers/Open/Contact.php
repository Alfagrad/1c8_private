<?php

namespace App\Http\Controllers\Open;

use App\Http\Controllers\Controller;
use App\Models\Page;

class Contact extends Controller
{

    public function __invoke()
    {
        $service = Page::find(23);

        return view('open.contact', compact('service'));
    }

}
