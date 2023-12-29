<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\DeliveryType
 *
 * @property int $id
 * @property string $text
 * @property int $action
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\DeliveryType whereAction($value)
 * @method static \Illuminate\Database\Query\Builder|\App\DeliveryType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\DeliveryType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\DeliveryType whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\DeliveryType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DeliveryType extends Model
{

    protected $fillable = ['text', 'action'];

}
