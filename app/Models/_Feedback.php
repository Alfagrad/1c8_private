<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Feedback
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $profile_id
 * @property string $client_name
 * @property string $email
 * @property string $attach
 * @property bool $is_confidential
 * @property string $comment
 * @property bool $feedback_type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Profile $profileId
 * @method static \Illuminate\Database\Query\Builder|\App\Feedback whereAttach($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Feedback whereClientName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Feedback whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Feedback whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Feedback whereFeedbackType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Feedback whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Feedback whereIsConfidential($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Feedback whereProfileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Feedback whereUpdatedAt($value)
 */
class Feedback extends Model
{
    protected $guarded = [];

    protected $table = 'feedbacks';

    public function profileId()
    {
        return $this->belongsTo('App\Models\Profile', 'profile_id', 'id');
    }


}
