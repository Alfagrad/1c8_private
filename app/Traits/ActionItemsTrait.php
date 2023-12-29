<?php

namespace App\Traits;

use App\Models\Item;

trait ActionItemsTrait
{
    public function getActionItems()
    {
        //TODO переделать эту шляпу. Одинаковые запросы.
        // берем спецпредложения
        $spec_items = Item::where('spec_price', 1)
            ->where(function($query){
                    $query->where('amount', '>', 0)->orWhere('locked', '>', 0);
                })
            ->orderBy('created_at', 'desc')
            ->get();

        // берем акционные товары
        $discount_items = Item::where('discount_str', '!=', '')
            ->where(function($query){
                    $query->where('amount', '>', 0)->orWhere('locked', '>', 0);
                })
            ->orderBy('discount_date', 'desc')
            ->get();

        foreach($discount_items as $key => $item) {
            // делим скидки
            $discounts = explode(';', $item->discount_str);

            // формируем строку дисконта
            $discount_str = "";

            // смотрим все ли дисконты действуют
            foreach ($discounts as $element) {

                // делим элементы скидки
                $element = explode('|', $element);

                // дата начала
                $date_start = $element[2];
                // дата окончания
                $date_end = $element[3];

                // если в диапзоне
                if (date('Y-m-d') >= $date_start && ($date_end == '0000-00-00' || date('Y-m-d') <= $date_end)) {

                    // дописываем
                    $discount_str .= "х";

                }
            }

            // если строка пуста
            if(!$discount_str) {
                // удаляем товар из коллекции
                $discount_items->forget($key);
            }

        }

        // объединяем спецпредложения и акции
        $items = $spec_items->merge($discount_items);

        return $items;

    }
}
