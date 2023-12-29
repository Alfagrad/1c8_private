<?php

namespace App\Traits;

use App\Models\Category;

trait MenuCategory
{

    public function getMenuCategory()
    {
        $menu_categories = Category::with('subCategory')
            ->where([
                ['id_1c', '!=', 7212],
                // 7222 - категория сервисных работ
                ['parent_1c_id', '0']
            ]);

        // если сервис
        if (\Auth::user()->profile->role == 'Сервис') {
            // исключаем Запчасти и Уцененные
            $menu_categories = $menu_categories->whereNotIn('id_1c', [7166, 7131]);
        }

        $menu_categories = $menu_categories
            ->orderBy('default_sort')
            ->get(['name', 'id_1c', 'image_sm', 'default_sort']);

        return $menu_categories;
    }

}
