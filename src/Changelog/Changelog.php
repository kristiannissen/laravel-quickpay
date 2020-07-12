<?php
/**
 *
 *
 */
namespace QuickPay\Changelog;

use GuzzleHttp\Client;

class Changelog {
    public function __construct() {
    }

    /**
     *
     */
    public static function get() : array {
        $client = new Client([
            'base_uri' => 'https://api.quickpay.net',
        ]);
        $response = $client->request('GET', 'changelog', [
            'headers' => [
                'Accept-Version' => 'v10',
            ]
        ]);
        $body = $response->getBody();
        return (array) json_decode($body->getContents());
    }
}
