<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Item;

class ItemSearchController extends Controller
{
    public function postIndex(Request $request)
    {
    	// проверяем переменную
    	$search_word = substr(htmlspecialchars(trim($request->search), ENT_QUOTES, 'utf-8'), 0, 300);

    	$items = Item::where([['is_component', 0], ['name', 'like', '%'.$search_word.'%'], ['1c_category_id', '!=', 3149]])->take(100)->orderBy('name')->get();

		return view('service.includes.cart_item_search_result', compact('items'));


//         $items_yes = clone $items;
//         $items_no = clone $items;
//         $items_yes_low_cost = clone $items;
//         $itemCode = clone $items;

//         if(count($searchKeywords) == 1) { // поиск по коду товара
//             $itemCode = $itemCode->OrWhere('1c_id',  $keyword )->first();
//             if($itemCode != null && $itemCode->{'1c_id'} == $keyword){
//                 $findByCode = $itemCode;
//             }
//             else{
//                 $findByCode = [];
//             }
//         }
//         else {
//             $findByCode = [];
//         }

//         $items_yes->where('count', '>', 0)->where('1c_category_id', '<>',  3149);
//         $items_no->where('count', '<=', 0);
//         $items_yes_low_cost = $items_yes_low_cost->where([['1c_category_id', '3149'], ['count', '>', 0]])->take(12)->get();

//         $categories = $categories->take($take)->get();
//         $itemCount = $items->count();
//         if($type == 0) {
//             $itemCount += $archive->count();
//         }
//         $items = $items->take(20)->get();
//         $archive = $archive->take(6)->get();
//         $items_yes = $items_yes->take(12)->get();

//         if($items_yes->count() < 12)
//         {
//             $take = 12 - $items_yes->count();
//             if($take < 0)
//                 $take = 0;
//         }
//         else{ $take = 0; }
//         $items_no = $items_no->take($take)->get();

//         $idToCart = Cart::where('profile_id', $profile->id)->pluck('count', 'item_id');

//         // наценка/скидка markup. Учитывается в отображении цен на сайте
//         $markup = $data['generalProfile']->markup;

//         // метка для сервисного центра
//         $is_service = intval($profile->is_service);

//         if (
//             $categories->count() or
//             $items->count() or
//             $archive->count() or
//             $findByCode != []
//         ) {
//             return view('search',
//                 [
//                     'categories' => $categories,
//                     'items_yes' => $items_yes->sortByDesc('price_bel'),
//                     'itemCode' => $findByCode,
//                     'items_no' => $items_no->sortByDesc('price_bel'),
//                     'archive' => $archive->sortByDesc('price_bel'),
//                     'items_yes_low_cost' => $items_yes_low_cost->sortByDesc('price_bel'),
//                     'idToCart' => $idToCart,
//                     'searchKeywords' => $searchKeywords,
//                     'itemCount' => $itemCount,
//                     'searchKeyword' => $searchKeyword,
//                     'type' => $request->type,
//                     'data_markup' => $markup,
//                     'is_service' => $is_service,
//                 ]);
//         } else {
//             return 'false';
//         }


    }
}
