<?php

namespace App\Listeners;

use App\Actions\IsTestDomain;
use App\Services\RocketSms;

abstract class SmsListener
{

    private $rocketSms;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(RocketSms $rocketSms)
    {
        $this->rocketSms = $rocketSms;
    }

    protected function smsSend($phone, $text)
    {
//        if((new IsTestDomain())->__invoke())
//            return;

        if(!empty($phone)){
            $this->rocketSms->send($phone, $text);
        }

    }

}
