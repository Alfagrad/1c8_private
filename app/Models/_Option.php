<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Option
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property string $type
 * @property string $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Option whereAlias($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Option whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Option whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Option whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Option whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Option whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Option whereValue($value)
 * @mixin \Eloquent
 */
class Option extends Model
{
    protected $guarded = [];
}
