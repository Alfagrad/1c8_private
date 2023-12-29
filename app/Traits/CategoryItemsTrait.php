<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use App\Models\Category;
use App\Models\Item;

trait CategoryItemsTrait
{
    public function getCategoryItems($code_array)
    {
        // пустая коллекция для правильной работы кода
        $collect = new Collection;

        // если 1с код категории
        $categories = Category::whereIn('id_1c', $code_array)->get();

        if($categories->count()) {

            foreach($categories as $category) {
                // берем товары категории
                $cat_items = Item::where([
                        ['1c_category_id', $category->id_1c],
                        ['in_archive', 0]
                    ])
                    ->where(function($query){
                        $query->where('amount', '>', 0)
                            ->orWhere('locked', '>', 0);
                    })
                    ->orderBy('name')
                    // ->take(4)
                    ->get();

                // если есть
                if($cat_items->count()) {
                    // добавляем в коллекцию
                    $collect->push([
                        'name' => $category->name,
                        'items' => $cat_items,
                    ]);
                }

                // смотрим дочерние категории
                if($category->subCategory) {

                    foreach($category->subCategory as $category_2) {

                        // берем товары категории
                        $cat_items_2 = Item::where([
                                ['1c_category_id', $category_2->id_1c],
                                ['in_archive', 0]
                            ])
                            ->where(function($query){
                                $query->where('amount', '>', 0)
                                    ->orWhere('locked', '>', 0);
                            })
                            ->orderBy('name')
                            // ->take(4)
                            ->get();

                        // если есть
                        if($cat_items_2->count()) {
                            // добавляем в коллекцию
                            $collect->push([
                                'name' => $category_2->name,
                                'items' => $cat_items_2,
                            ]);
                        }

                        // смотрим дочерние категории
                        if($category_2->subCategory) {

                            foreach($category_2->subCategory as $category_3) {

                                // берем товары категории
                                $cat_items_3 = Item::where([
                                        ['1c_category_id', $category_3->id_1c],
                                        ['in_archive', 0]
                                    ])
                                    ->where(function($query){
                                        $query->where('amount', '>', 0)
                                            ->orWhere('locked', '>', 0);
                                    })
                                    ->orderBy('name')
                                    // ->take(4)
                                    ->get();

                                // если есть
                                if($cat_items_3->count()) {
                                    // добавляем в коллекцию
                                    $collect->push([
                                        'name' => $category_3->name,
                                        'items' => $cat_items_3,
                                    ]);
                                }

                                // смотрим дочерние категории
                                if($category_3->subCategory) {

                                    foreach($category_3->subCategory as $category_4) {

                                        // берем товары категории
                                        $cat_items_4 = Item::where([
                                                ['1c_category_id', $category_4->id_1c],
                                                ['in_archive', 0]
                                            ])
                                            ->where(function($query){
                                                $query->where('amount', '>', 0)
                                                    ->orWhere('locked', '>', 0);
                                            })
                                            ->orderBy('name')
                                            // ->take(4)
                                            ->get();

                                        if($cat_items_4->count()) {
                                            // добавляем в коллекцию
                                            $collect->push([
                                                'name' => $category_4->name,
                                                'items' => $cat_items_4,
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $collect;
    }
}
