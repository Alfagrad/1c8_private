<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TempProfileAddress
 *
 * @property int $id
 * @property int $temp_profile_id
 * @property string $address
 * @property string $comment
 * @property bool $state
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfileAddress whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfileAddress whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfileAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfileAddress whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfileAddress whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfileAddress whereTempProfileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfileAddress whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TempProfileAddress extends Model
{
    protected $guarded = [];
}
