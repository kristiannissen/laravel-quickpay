<?php
/**
 * Documentation https://learn.quickpay.net/tech-talk/api/services/#ping
 *
 * Use this to test connectivity to the API
 */
namespace QuickPay\Ping;

use GuzzleHttp\Client;
use QuickPay\Ping\Pong;

class Ping
{
    /**
     * Send ping request to https://api.quickpay.net/ping
     */
    public static function send(): Pong
    {
        $client = new Client([
            'base_uri' => 'https://api.quickpay.net',
        ]);
        $response = $client->request('GET', 'ping', [
            'headers' => [
                'Accept-Version' => 'v10',
            ],
        ]);
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            return new Pong((array) json_decode($body->getContents()));
        }
        return new Pong();
    }
}
