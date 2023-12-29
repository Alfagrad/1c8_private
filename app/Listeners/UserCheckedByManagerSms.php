<?php

namespace App\Listeners;

use App\Events\UserCheckedByManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserCheckedByManagerSms extends SmsListener
{

    /**
     * Handle the event.
     */
    public function handle(UserCheckedByManager $event): void
    {
        $this->smsSend($event->phone, 'Ваша регистрация на alfastok.by подтверждена');
    }
}
