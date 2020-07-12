<?php
/**
 *
 *
 */
namespace QuickPay\Account;

use GuzzleHttp\Client;

class Account {
    protected $attributes = [];

    public function __construct(array $attributes = []) {
    
    }

    /**
     * Performs GET request
     *
     * @return object Account
     */
    public static function get() : Account {
        $client = new Client([
            'base_uri' => 'https://api.quickpay.net',
        ]);
        $response = $client->request('GET', 'account', [
            'headers' => [
                'Accept-Version' => 'v10',
                'Accept' => 'application/json'
            ],
            'auth' => [],
        ]);
    }
}
