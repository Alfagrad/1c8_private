<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {

        // берем новости
        $news = News::where([['is_active', 1], ['for_opt', 1]])->orderBy('created_at', 'Desc')->get();

        return view('news.index', compact('news'));
    }

    public function show(Request $request, $alias){

        $news = News::where('alias', $alias)->firstOrFail();

        if($news->is_active != 1){
            return redirect('/news', 301);
        }

        return view('news.show', compact('news'));
    }


}
