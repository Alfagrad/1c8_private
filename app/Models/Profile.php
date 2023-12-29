<?php

namespace App\Models;

use App\Actions\IsServiceCanBuy;
use Hamcrest\Core\Is;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profile extends Model
{
    protected $guarded = [];

    protected $fillable = ['user_id','role','is_blocked','name','email','company_name','unp','phone','phone_mob','position',
        'partner_uuid','direct_token','direct_permission','status','is_checked','deleted',];

    public function addresses()
    {
        return $this->hasMany('App\Models\ProfileAddress');
    }

    public function partner()
    {
        return $this->hasOne('App\Models\Partner', 'uuid', 'partner_uuid');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function subscribe()
    {
        return $this->hasOne('App\Models\ProfileSubscribe');
    }

    public function debt()
    {
        return $this->hasMany('App\Models\ProfileDebt', 'unp', 'unp')->orderBy('realization_date');
    }

    public function isService(): bool
    {
        return $this->role == 'Сервис';
    }

    public function isDealer(): bool
    {
//        return false;
        return $this->role == 'Клиент';
    }

    public function isDealerService(): bool
    {
        $services = [
            'kostrov905@gmail.com',
            'lepelby@gmail.com',
            'konon2@yandex.by',
            'yurok6464308@yandex.ru',
            'remarsenal@mail.ru',
            'Swibowich73@gmail.ru',
            'marina.burda.2021@mail.ru',
            'sergei19790304@mail.ru',
            'yaretsvladimir@yandex.by',
            'kpplus19@mail.ru',
            'benzoserwis@yandex.ru'
        ];
        return $this->isService() && in_array($this->email, $services) && app(IsServiceCanBuy::class)();
    }

    // public function repairs()
    // {
    //     return $this->hasMany('App\Models\ProfileRepair');
    // }


     public function carts(?int $cartOrderId = null): HasMany
     {
         $relation = $this->hasMany('App\Models\Cart');
         if(func_num_args() == 1) {
             $relation->where('cart_order_id', $cartOrderId);
         }
         return $relation;
     }

    public function cartOrders(): HasMany
    {
        return $this->hasMany(CartOrder::class);
    }

}
