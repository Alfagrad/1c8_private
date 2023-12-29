<?php

namespace App\Helpers;

use App\Item;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Model;

/**
 * Функции для пересчета цен при обновлении валют
 * Class ChangeCurrencyHelper
 * @package App\Helpers
 */

class ChangeCurrencyHelper {

    private $headerUsd;
    private $headerUsdMrc;


    public function reCheck(){

       $this->headerUsd = setting('header_usd');
       $this->headerUsdMrc = setting('header_usd_mrc');

       Item::chunk(50, function ($items) {
           foreach ($items as $item) {
                $this->changePrice($item);
           }
       });

    }


    public function changePrice($item){
        $item->price_bel = ceil(intval($this->headerUsd * $item->price_usd * 1000) / 10) / 100;
        $item->price_bel_1 = ceil(intval($this->headerUsd * $item->price_usd_1 * 1000) / 10) / 100;
        $item->price_bel_2 = ceil(intval($this->headerUsd * $item->price_usd_2 * 1000) / 10) / 100;

        $item->price_min_bel = ceil(intval($this->headerUsd * $item->price_min_usd * 1000) / 10) / 100;

        $real_mr_bel = $this->headerUsdMrc * $item->price_mr_usd; // мрц без округления
        if($real_mr_bel > 100) {
            $round_mr_bel = ceil(intval($real_mr_bel * 10) / 10); // округляем до целого в большую сторону
        } elseif($real_mr_bel > 50) {
            $round_mr_bel = ceil(intval($real_mr_bel * 100) / 10) / 10; // округляем до 1 знака после запятой в большую сторону
        } else {
            $round_mr_bel = round($real_mr_bel, 2);  // округляем до 2 знака после запятой математически
        }
        $item->price_mr_bel = $round_mr_bel;

        if($item->discounted){
            $listDiscount = [];
            $prevListDiscounts = explode(';',  $item->discounted);
            $prevListDiscounts = array_diff($prevListDiscounts, array('')); // Удаляем пустые
            $item->discounted_rub = '';
            foreach ($prevListDiscounts as $pLD ){
                list($count, $price) = explode('-', $pLD);
                $listDiscount[] = [
                    'count' => $count,
                    'price' => ceil(intval($this->headerUsd * $price * 1000) / 10) / 100,
                ];
                $item->discounted_rub .= $count .'-'.  ceil(intval($this->headerUsd * $price * 1000) / 10) / 100 . ';';
            }
        }

        $item->save();
        return true;
    }

}
