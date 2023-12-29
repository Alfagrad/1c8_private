<?php

namespace App\Actions;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class RequestToUT
{

    private $response;

    public function __invoke($xml): int
    {

        // отправка заказа в 1с8
        $client = new GuzzleClient();
        $credentials = base64_encode(config('ut.login').':'.config('ut.password'));
        $status_code = '';

        try {

            // $response = $client->post('http://93.125.106.243/UT_Site/hs/site.exchange/updInf', [
            // $response = $client->get('http://93.125.106.243/UT_Copy/hs/site.exchange/test', [
            $this->response = $client->post('http://'.config('ut.ip').'/UT_Copy/hs/site.exchange/updInf', [
                'connect_timeout' => 10,
                'headers' => [
                    'objectType' => 'request',
                    'Authorization' => 'Basic ' . $credentials,
                    'Content-Type' => 'application/xml',
                ],
                'body' => $xml,
            ]);

            $status_code = $this->response->getStatusCode();
            // $body = $response->getContent();

        } catch (GuzzleException $e) {

            Log::error('RequestToUT - GuzzleException', ['message' => $e->getMessage(), 'body' => $xml, 'credentials' => config('ut.login').':'.config('ut.password')]);
            return 500;

        }

        return $status_code;
    }

}
