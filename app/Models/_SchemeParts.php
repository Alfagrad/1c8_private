<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchemeParts extends Model
{
    protected $table = 'scheme_parts';
    protected $fillable = ['scheme_id', 'scheme_no', 'number_in_schema', 'spare_id', 'parent_id'];
    public $timestamps = false;

    public function getItem()
    {
        return $this->belongsTo('App\Models\Item', 'spare_id', '1c_id');
    }

    public function getParent()
    {
        return $this->belongsTo('App\Models\Item', 'parent_id', '1c_id');
    }

    public function getScheme()
    {
        return $this->belongsTo('App\Models\Scheme', 'scheme_id', 'scheme_id');
    }
}
