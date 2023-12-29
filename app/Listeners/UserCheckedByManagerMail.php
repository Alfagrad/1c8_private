<?php

namespace App\Listeners;

use App\Events\UserCheckedByManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserCheckedByManagerMail extends MailListener
{

    /**
     * Handle the event.
     */
    public function handle(UserCheckedByManager $event): void
    {
        $this->mail('emails.regAccept', $event->email, 'Ваша регистрация на сайте Альфасток');
    }
}
