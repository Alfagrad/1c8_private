<?php

namespace App\Traits;

use App\Models\Item;

trait CheapItemsTrait
{
    public function getCheapItems($items)
    {
        //TODO переделать уценку
//        foreach ($items as $item) {
//            // берем товары комплектации из каталога уцененных
//            $cheap_items = Item::where([
//                    ['1c_category_id', '7215'],
//                    ['id_1c', 'like', $item->id_1c.'%'],
//                    ['id_1c', '!=', $item->id_1c],
//                ])
//                ->where(function($query){
//                    $query->where('amount', '>', 0)->orWhere('locked', '>', 0);
//                })
//                ->orderBy('name')
//                ->get();
//
//            // если не не пусто
//            if ($cheap_items->count()) {
//                // добавляем в коллекцию
//                $item->cheap_items = $cheap_items;
//            }
//        }

        return $items;
    }
}
