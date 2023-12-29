<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TempProfileContact
 *
 * @property int $id
 * @property int $temp_profile_id
 * @property string $name
 * @property string $phone
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfileContact whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfileContact whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfileContact whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfileContact wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfileContact whereTempProfileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfileContact whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TempProfileContact extends Model
{
    protected $guarded = [];
}
