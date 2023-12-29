<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ArrivalItem
 *
 * @property int $id
 * @property string $title
 * @property string $link
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\ArrivalItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArrivalItem whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArrivalItem whereIsActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArrivalItem whereLink($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArrivalItem whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArrivalItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArrivalItem extends Model
{
    //
    protected $fillable = ['title','link','is_active'];

}
