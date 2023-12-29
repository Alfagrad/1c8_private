<?php

namespace App\Listeners;

class MailListener
{

    protected $mail;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(\App\Actions\Mail $mail)
    {
        $this->mail = $mail;
    }

}