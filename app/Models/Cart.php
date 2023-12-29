<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Cart
 *
 * @property int $id
 * @property int $profile_id
 * @property int $item_id
 * @property int $count
 * @property int $cart_order_id
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property-read \App\Item $item
 * @method static \Illuminate\Database\Query\Builder|\App\Cart whereCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cart whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cart whereItemId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cart whereProfileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cart whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Cart extends Model
{
    protected $guarded = ['id'];

    protected $fillable = ['profile_id','item_1c_id','item_uuid','count','cart_order_id'];

    public function item()
    {
        return $this->hasOne('App\Models\Item', 'id_1c', 'item_1c_id');
    }

    public function getSumAttribute(): float
    {
        return $this->count * $this->item->price_rub;
    }

    public function getSumWeightAttribute(): float
    {
        return $this->item->weight * $this->count;
    }

    public function scopeMy(Builder $query): Builder
    {
        return $query->where('profile_id', auth()->user()->profile->id);
    }

}
