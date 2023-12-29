<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Events\UserCheckedByManager;
use App\Listeners\OrderCreatedEmailToManager;
use App\Listeners\OrderCreatedEmailToUser;
use App\Listeners\OrderCreatedToUT;
use App\Listeners\UserCheckedByManagerMail;
use App\Listeners\UserCheckedByManagerSms;
use App\Models\CartOrder;
use App\Observers\CartOrderObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        OrderCreated::class => [
            OrderCreatedToUT::class,
            OrderCreatedEmailToManager::class,
            OrderCreatedEmailToUser::class
        ],
        UserCheckedByManager::class => [UserCheckedByManagerMail::class, UserCheckedByManagerSms::class]
    ];

    protected $observers = [
        CartOrder::class => [CartOrderObserver::class]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
