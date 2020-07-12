<?php

namespace QuickPay;

use GuzzleHttp\Client;

abstract class QuickPayHttpClient
{
    /**
     *
     */
    abstract protected function getUri(): string;

    public function get()
    {
        $client = new Client([
            'base_uri' => 'https://api.quickpay.net',
        ]);
        $response = $client->request('GET', $this->getUri(), [
            'headers' => [
                'Accepted-Version' => 'v10',
            ],
        ]);
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            return (array) json_decode($body->getContents());
        }
    }
}
