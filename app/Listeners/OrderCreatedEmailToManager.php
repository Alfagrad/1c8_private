<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class OrderCreatedEmailToManager
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
        $profile = $event->order->profile;
        // Отправляем сообщения
        if($profile->isService()) {
            $client_type_str = " от Сервисного центра";
        } else {
            $client_type_str = " от Контрагента";
        }

        // хэдеры
        $email_headers['subject'] = "Заказ ".$event->order->id.$client_type_str;
        $email_headers['email_from'] = \Voyager::setting('email_from');
        $email_headers['headers_from'] = 'Alfastok';
        $email_headers['email_to'] = $profile->partner?->manager;
        if(!$email_headers['email_to']){
            if(profile()->isService()) {
                $email_headers['email_to'] = \Voyager::setting('email_to_service'); // руководителю сервисного отдела
            } else {
                $email_headers['email_to'] = \Voyager::setting('email_to_other'); // руководителю отдела продаж
            }
            Log::info('mail to ROP');
        }

        \Mail::send('emails.order', ['profile' => $profile, 'order' => $event->order], function ($message) use ($email_headers) {
            $message->from($email_headers['email_from'], $email_headers['headers_from']);
            $message->to($email_headers['email_to'])->subject($email_headers['subject']);
        });

    }
}
