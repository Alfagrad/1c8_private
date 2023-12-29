<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\OrderItem
 *
 * @property int $id
 * @property int $order_id
 * @property int $item_id
 * @property string $item_name
 * @property float $item_price
 * @property int $item_count
 * @property float $item_sum_price
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\OrderItem whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\OrderItem whereItemCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\OrderItem whereItemId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\OrderItem whereItemName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\OrderItem whereItemPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\OrderItem whereItemSumPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\OrderItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Item $item
 */
class OrderItem extends Model
{
    protected $guarded = [];

    protected $fillable = ['order_id', 'item_uuid', 'item_1c_id', 'item_name', 'item_price', 'item_count', 'item_sum_price'];

    public function item()
    {
        return $this->hasOne('App\Models\Item', 'id_1c', 'item_1c_id');
    }

}
