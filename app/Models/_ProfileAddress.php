<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProfileAddress
 *
 * @property int $id
 * @property int $profile_id
 * @property string $address
 * @property string $comment
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileAddress whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileAddress whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileAddress whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileAddress whereProfileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileAddress whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Profile $profileId
 */
class ProfileAddress extends Model
{
    protected $guarded = [];

    public function profileId()
    {
        return $this->belongsTo('App\Models\Profile', 'profile_id', 'id');
    }

}
