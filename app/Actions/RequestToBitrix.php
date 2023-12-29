<?php

namespace App\Actions;

class RequestToBitrix
{

    public function __invoke(int $bitrixId = 1366, string $message = ''): string
    {

        $queryUrl = 'https://alfastok.bitrix24.by/rest/386/r7el2b50ll7no1qg//im.message.add.json';
        $queryData = http_build_query(array(
            'USER_ID' => $bitrixId,
            'MESSAGE' => $message,
        ));

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $queryUrl,
            CURLOPT_POSTFIELDS => $queryData,
        ));

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;

    }

}
