<?php

namespace App\Services;

use GuzzleHttp\Client;

class RocketSms
{

    public function send($phone, $text = '')
    {
        $client = new Client();
        $password = md5(config('rocketSms.pass'));
        $username = config('rocketSms.login');
        $url = config('rocketSms.url');
        $sms = 'username='.$username.
            '&password='.$password.
            '&phone='.$phone.
            '&text='.$text;
        $client->post($url . $sms, []);
    }

}