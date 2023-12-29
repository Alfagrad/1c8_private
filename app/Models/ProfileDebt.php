<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileDebt extends Model
{
    protected $guarded = [];

    protected $fillable = ['unp','realization_date','realization_sum','pay_date','sum',];

}
