<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    protected $guarded = [];
    protected $fillable = ['profile_id','name','email','phone','viber','skype','bitrix_code','department','assistant'];
}
