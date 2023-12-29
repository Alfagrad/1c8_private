<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\TestJob;

class MailChimpJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
// !!! РАЗОБРАТЬСЯ КАК РАБОТАЕТ !!!
        // добавляем в подписки

        $mailChimp = \App::make('\App\Helpers\MailChimpAlfa');
        $fieldsApiList = [
            'xls_weekly'=> 'a0cd297c60',
            'news' => '2da2854a7f',
            'new_items' => 'dceb303894',
            'change_price' => '23fc1a1795'
        ];
        foreach ($fieldsApiList as $k => $v) {
            $mailChimp->addSubscriber($list = $fieldsApiList[$k], $email, $name);
        }
    }
}
