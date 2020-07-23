<?php
/**
 *
 */
namespace QuickPay;

use GuzzleHttp\Client;

abstract class QuickPayService
{
    protected $client;

    public function __construct()
    {
        $this->client = QuickPayService::getClient();
    }

    public static function getClient()
    {
        return new Client([
            'base_uri' => 'https://api.quickpay.net',
        ]);
    }

    public function withHeaders()
    {
        return [
            'auth' => ['', config('quickpay.api_key')],
            'headers' => [
                'Accept-Version' => config('quickpay.version'),
                'Accept' => 'application/json',
            ],
        ];
    }
}
