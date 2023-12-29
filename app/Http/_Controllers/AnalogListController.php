<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\SchemeParts;

class AnalogListController extends Controller
{
    public static function index()
    {
        // в товарах, у которых могут быть аналоги, очищаем поле analog_list
        \DB::table('items')->where('is_component', 1)->update(['analog_list' => '']);

        // $items = Item::where('is_component', 1);
        // $items->update(['analog_list' => '']);

        // определяем коды товаров, у которых могут быть аналоги
        $spares_analogs = Item::where('is_component', 1)->pluck('1c_id');

        // собираем аналоги
        foreach($spares_analogs as $item) {
            $scheme_parts = SchemeParts::where('spare_id', $item)->get();

            $itm = collect();

            foreach ($scheme_parts as $part) {
                $tmp = SchemeParts::where([
                    ['scheme_id', $part->scheme_id],
                    ['number_in_schema', $part->number_in_schema],
                    ['spare_id', '!=', $item],
                ])->pluck('spare_id');
                $itm->push($tmp);
            }

            $itm = $itm->flatten()->unique();

            // убираем товары, кот. нет в наличии
            foreach($itm as $key => $value) {
                if(Item::where('1c_id', $value)->first(['count'])->count <= 0) {
                    $itm->forget($key);
                }
            }

            // если массив не пустой, записываем коды аналогов в БД
            if(count($itm)) {
                \DB::table('items')
                    ->where('1c_id', $item)
                    ->update([
                        'analog_list' => implode(',', $itm->values()->toArray()),
                    ]);
            }
        }

        exit('Аналоги прописаны в БД');
    }
}
