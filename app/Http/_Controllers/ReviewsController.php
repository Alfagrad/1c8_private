<?php

namespace App\Http\Controllers;

use App\Models\ReviewItem;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function index(Request $request){

        // берем обзоры
        $reviews = ReviewItem::where('is_active', 1)->orderBy('created_at', 'Desc')->get();

        return view('reviews.index', compact('reviews'));
    }

    public function show(Request $request, $alias){
        $review = ReviewItem::where('alias', $alias)->firstOrFail();

        if($review->is_active != 1){
            return redirect('/reviews', 301);
        }

        return view('reviews.show', compact('review'));
    }

}
