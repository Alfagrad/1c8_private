<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckStatusRequest;
use App\Models\ProfileRepair;
use Illuminate\Http\Request;

class CheckStatusController extends Controller
{
    public function index(CheckStatusRequest $request)
    {

        $check = ProfileRepair::where('1c_id', $request->receiptNumber)->pluck('state')->first();

        if(is_null($check))
            return 0;


        return $check;
    }
}
