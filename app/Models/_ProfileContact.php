<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProfileContact
 *
 * @property int $id
 * @property int $profile_id
 * @property string $name
 * @property string $phone
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileContact whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileContact whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileContact whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileContact wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileContact whereProfileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileContact whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Profile $profileId
 */
class ProfileContact extends Model
{
    protected $guarded = [];

    public function profileId()
    {
        return $this->belongsTo('App\Models\Profile', 'profile_id', 'id');
    }


}
