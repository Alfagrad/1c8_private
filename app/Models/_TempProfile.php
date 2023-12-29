<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TempProfile
 *
 * @property int $id
 * @property int $real_profile_id
 * @property int $user_id
 * @property int $1c_id
 * @property string $name
 * @property string $email
 * @property string $company_name
 * @property string $company_address
 * @property string $unp
 * @property string $bank_account
 * @property string $bank_name
 * @property string $trade_object
 * @property string $shops
 * @property string $coverage_area
 * @property bool $is_checked Проверено?
 * @property string $manager_email
 * @property string $manager_viber
 * @property string $manager_skype
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property bool $is_blocked
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TempProfileAddress[] $address
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TempProfileContact[] $contact
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile where1cId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereBankAccount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereBankName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereCompanyAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereCompanyName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereCoverageArea($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereIsBlocked($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereIsChecked($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereManagerEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereManagerSkype($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereManagerViber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereRealProfileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereShops($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereTradeObject($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereUnp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TempProfile whereUserId($value)
 * @mixin \Eloquent
 */
class TempProfile extends Model
{
    protected $guarded = [];


    public function contact()
    {
        return $this->hasMany('App\Models\TempProfileContact');
    }

    public function address()
    {
        return $this->hasMany('App\Models\TempProfileAddress');
    }
}
