<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    protected $guarded = [];

    protected $fillable = ['uuid','partner_uuid','unp','name','date','date_end','formula'];

    public function scopeBeforeDate(Builder $query): void
    {
        $query->where('date', '<=', date('Y-m-d'));
    }

    public function scopeActiveDate(Builder $query): void
    {
        $query->where(function ($query) {
            $query->where('date_end', '0000-00-00')->orWhere('date_end', '>=', date('Y-m-d'));
        });
    }

}
