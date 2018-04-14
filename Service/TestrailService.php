<?php

namespace Test\TestrailBundle\Service;

use function curl_init;
use function curl_setopt;
use const CURLOPT_HTTPHEADER;
use function json_decode;

class TestrailService
{
    private $cacheApp;

    /**
     * TestrailService constructor.
     * @param $cacheApp
     */
    public function __construct($cacheApp)
    {
        $this->cacheApp = $cacheApp;
    }

    public function getData()
    {
        $curl = curl_init();
        $url = 'https://boosta.testrail.io/index.php?/api/v2/get_runs/6';

        $headers = [
            'Content-Type:application/json',
            'Authorization: Basic Y29kZWNyZXdfcHJvZEBib29zdGEuY286YzBkZUNyZXc='
        ];

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    public function getResponseData()
    {
        $dataId = 'project id 6';
        $cachedItem = $this->cacheApp->getItem($dataId);

        if (!$cachedItem->isHit()) {
            $cachedItem->set(json_decode($this->getData()));
            $this->cacheApp->save($cachedItem);
        }

        $cachedItem = $cachedItem->get();

        $this->cacheApp->deleteItem($dataId);

        $failedRunList = [];

        foreach ($cachedItem as $item) {
            $failedCount = $item->failed_count;

            if ($failedCount > 0) {
                $failedRunList[] = $item;
                break;
            }
        }

        return $failedRunList;
    }


}