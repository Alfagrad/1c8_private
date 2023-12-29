<?php

namespace App\Http\Controllers;

// use App\Models\Cart;
// use App\Models\Item;
// use App\Models\User;
use App\Models\MenuSite;
// use App\Models\Option;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $options;
    // protected $profile;

    public function __construct(Request $request)
    {

        // \Debugbar::disable();
        // $imageResize = \App::make('\App\Helpers\ImageResize');
        // $this->options = Option::get()->pluck('value', 'alias');
        $menu = MenuSite::with(['subMenu' => function ($query) {
            $query->where('is_active', 1)->orderBy('pos', 'ASC');
        }])->where('parent_id', 0)->orderBy('pos')->get();

        $v = [
            // 'imageResize' => $imageResize,
            // 'options' =>  $this->options,
            'menu' => $menu,
        ];

        // $inCart = [
        //     'price' => 0,
        //     'count' => 0
        // ];

        // if($this->profile){
        //     //$this->profile = $request->user()->profile()->with('address', 'contact', 'repairs', 'depts')->first();
        //     $v['generalProfile'] = $this->profile;
        //     $repairs = $this->profile->repairs;
        //     $depts = $this->profile->depts;
        //     $v['repairsCountInWork'] = $repairs->where('state', config('constants.repair.inWork'))->count();
        //     $v['repairsCountReady'] = $repairs->where('state', config('constants.repair.ready'))->count();
        //     $v['deptSum'] = $depts->sum(function ($d) {
        //         return $d['dept'];
        //     });

        //     $cart = Cart::with('item.gift')->where('profile_id', $this->profile->id)->get();

        //     foreach ($cart as $c){
        //         $inCart['price'] += $c->count *  $c->item->priceFromCountBYN($c->count);
        //         $inCart['count'] += $c->count;
        //         if($c->item->gift){
        //             $inCart['count'] +=  $c->count;
        //         }
        //     }

        //     $v['inCart'] = $inCart;
        // }

        \View::share($v);
    }


//     public function getUserData(Request $request){
//         $inCart = [
//             'price' => 0,
//             'count' => 0
//         ];

//         $this->profile = $request->user()->profile()->with('address', 'contact', 'repairs', 'depts')->first();
//         Item::$priceType = $this->profile->type_price;
//         $v['generalProfile'] = $this->profile;
//         $repairs = $this->profile->repairs;
//         $depts = $this->profile->depts;
//         $v['repairsCountInWork'] = $repairs->where('state', config('constants.repair.inWork'))->count();
//         $v['repairsCountReady'] = $repairs->where('state', config('constants.repair.ready'))->count();
//         $v['deptSum'] = $depts->sum(function ($d) {
//             return $d['dept'];
//         });

//         // расчетная наценка/скидка markup
//         $markup = $this->profile->markup / 100 + 1;

//         // dd($markup);

//         $cart = Cart::with('item.gift')->where('profile_id', $this->profile->id)->get();
//         foreach ($cart as $c){
//             if(isset($c->item->bel_price)){

//                 if($c->item->bel_price != 0) {
//                     $maxMarkup = $c->item->price_mr_bel / $c->item->bel_price;
//                     // если расчетный больше, используем максимальный
//                     if($markup > $maxMarkup) $item_markup = $maxMarkup;
//                         else $item_markup = $markup;
//                 } else {
//                     $item_markup = 1;
//                 }

//                 $inCart['price'] += $c->count * ceil($c->item->priceFromCountBYN($c->count) * $item_markup * 100) / 100;

//                 $inCart['count'] +=  $c->count;
//                 if($c->item->gift){
//                     $inCart['count'] += $c->count;
//                 }
//             }
//         }

//         $v['inCart'] = $inCart;
// // dd($inCart);

//         return $v;
//     }

}
