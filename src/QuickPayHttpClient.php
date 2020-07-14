<?php

namespace QuickPay;

use GuzzleHttp\Client;

class QuickPayHttpClient
{
    /**
     *
     */
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.quickpay.net',
        ]);
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
