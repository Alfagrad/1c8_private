<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVisit extends Model
{
    protected $guarded = [];

    protected $fillable = ['user_id', 'ip', 'company_name'];
}
