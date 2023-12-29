<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;

class DeliveryPageController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getUserData($request);
        $page = Page::find(3);
        $data['article'] = $page;

        return view('service.delivery_page')->with($data);
    }
}
