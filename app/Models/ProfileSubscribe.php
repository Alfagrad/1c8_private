<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProfileSubscribe
 *
 * @property int $id
 * @property int $profile_id
 * @property bool $xls_weekly
 * @property bool $news
 * @property bool $new_items
 * @property bool $copy_order
 * @property bool $change_price
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileSubscribe whereChangePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileSubscribe whereCopyOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileSubscribe whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileSubscribe whereNewItems($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileSubscribe whereNews($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileSubscribe whereProfileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProfileSubscribe whereXlsWeekly($value)
 * @mixin \Eloquent
 * @property-read \App\Profile $profileId
 */
class ProfileSubscribe extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $fillable = ['partner_uuid','profile_id','xls_weekly','news','new_items','copy_order','change_pric',];

    public function profileId()
    {
        return $this->belongsTo('App\Models\Profile', 'profile_id', 'id');
    }
}
