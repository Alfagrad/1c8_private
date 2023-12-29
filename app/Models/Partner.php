<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

class Partner extends Model
{
    protected $guarded = [];

    protected $fillable = ['uuid', 'parent_uuid', 'name', 'address', 'warehouse', 'manager', 'phone', 'site', 'latitude', 'longitude', 'brands', 'logo', 'deleted'];

    public function manager()
    {
        return $this->hasOne('App\Models\Manager', 'email', 'manager');
    }

    public function childs(): HasMany
    {
        return $this->hasMany(self::class, 'parent_uuid', 'uuid');
    }

    public function partners(): Builder
    {
        return $this->where('parent_uuid', $this->uuid);
    }

}
