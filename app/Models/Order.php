<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Order
 *
 * @property int $id
 * @property int $profile_id
 * @property string $delivery
 * @property string $calculation
 * @property int $savings
 * @property float $weight
 * @property float $price
 * @property bool $is_send
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $address
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrderItem[] $items
 * @method static \Illuminate\Database\Query\Builder|\App\Order whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order whereCalculation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order whereDelivery($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order whereIsSend($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order whereProfileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order whereSavings($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Order whereWeight($value)
 * @mixin \Eloquent
 * @property-read \App\Profile $profileId
 */
class Order extends Model
{
    protected $guarded = [];
    protected $fillable = ['profile_id','agreement_uuid','calculation','delivery','delivery_partner_uuid','address','savings','weight','price',
        'is_send','personal_discount','general_discount','comment','client_name','client_phone','item_uuid','item_name','item_1c_id','item_sn','item_sale_date',
        'item_defect','item_diagnostic','conclusion','act','is_replay',];

    public function items()
    {
        return $this->hasMany('App\Models\OrderItem');
    }

    public function conclusions()
    {
        return $this->hasOne('App\Models\ServiceConclusion', 'order_id', 'id');
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function profileId()
    {
        return $this->belongsTo('App\Models\Profile', 'profile_id', 'id');
    }
}
