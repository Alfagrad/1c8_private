<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ItemAction
 *
 * @property int $id
 * @property int $1c_id
 * @property int $item_id
 * @property int $item_1c_id
 * @property int $count_items
 * @property float $discount
 * @property int $discount_type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\ItemAction where1cId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ItemAction whereCountItems($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ItemAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ItemAction whereDiscount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ItemAction whereDiscountType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ItemAction whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ItemAction whereItem1cId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ItemAction whereItemId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ItemAction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ItemAction extends Model
{
    protected $guarded = [];


}
