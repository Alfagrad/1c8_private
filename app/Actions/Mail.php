<?php

namespace App\Actions;

class Mail
{

    protected $from;

    public function __construct()
    {
        $this->from = setting('email_from');
    }

    public function __invoke($view, $to, $subject, array $data = [])
    {
        \Illuminate\Support\Facades\Mail::send(
            $view,
            $data,
            function ($message) use ($to, $subject) {
                $message->from($this->from, 'Alfastok');
                $message->to($to)->subject($subject);
            });
    }

}
