<?php

namespace Test\TestrailBundle\Service;

use function curl_init;
use function curl_setopt;
use const CURLOPT_HTTPHEADER;
use function json_decode;

class TestrailService
{
    public function getDataByCurl()
    {

        $curl = curl_init();
        $url = 'https://boosta.testrail.io/index.php?/api/v2/get_runs/6';

        $headers = [
            'Content-Type:application/json',
            'Authorization: Basic Y29kZWNyZXdfcHJvZEBib29zdGEuY286YzBkZUNyZXc='
        ];

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    public function getResponseData()
    {
        $data = json_decode($this->getDataByCurl());

        $failedRunList = [];

        foreach ($data as $item) {
            $failedCount = $item->failed_count;

            if ($failedCount > 0) {
                $failedRunList[] = $item;
            }
        }

        return $failedRunList;
    }
}