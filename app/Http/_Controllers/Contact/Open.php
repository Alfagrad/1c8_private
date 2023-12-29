<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Page;

class Open extends Controller
{

    public function __invoke()
    {
        $service = Page::find(23);

        return view('contact.open', compact('service'));
    }

}
