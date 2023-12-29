<?php

namespace App\Actions;

use App\Models\Item;

class IsItemCanDiscount
{

    public function __invoke(Item $item): bool
    {
        // TODO добавить уценку и запчасть
        return !$item->adjustable;
    }

}
