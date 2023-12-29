<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderCreatedEmailToUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $email_headers['subject'] = "Заказ ".$event->order->id." на Alfastok.by";
        $email_headers['email_from'] = \Voyager::setting('email_from');
        $email_headers['headers_from'] = 'Alfastok';

        $profile = $event->order->profile;

        if($profile->role == 'Сервис') {
            return;
        }

        // если несколько адресов, отправляем на первый
        $client_emails = explode(';', $profile->email);

//        if ($profile->subscribe->copy_order and $client_emails[0]) {
            $email_headers['email_to'] = trim($client_emails[0]);
            if($email_headers['email_to']) {
                \Mail::send('emails.orderToClient', ['profile' => $profile, 'order' => $event->order], function ($message) use ($email_headers, $profile) {
                    $message->from($email_headers['email_from'], $email_headers['headers_from']);
                    $message->to($email_headers['email_to'])->subject($email_headers['subject']);
                });
            }
//        }

    }
}
