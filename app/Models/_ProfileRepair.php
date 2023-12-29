<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileRepair extends Model
{

    protected $connection = 'mysql2';

    protected $guarded = [];

    // protected $dates= [
    //     'created_at',
    //     'updated_at',
    //     'date'
    // ];

    // public function getRepairLabelAttribute(){
    //     if($this->state == config('constants.repair.inWork')){
    //         return 'В работе';
    //     } elseif($this->action == config('constants.repair.ready')) {
    //         return 'новика';
    //     } elseif($this->action == config('constants.repair.issued')){
    //         return 'скидка';
    //     } else {
    //         return '';
    //     }
    // }

    // public function profileId()
    // {
    //     return $this->belongsTo('App\Models\Profile', 'profile_id', 'id');
    // }

}
