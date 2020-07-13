<?php
/**
 *
 */
namespace QuickPay\Repository;

use QuickPay\QuickPayModel;
use QuickPay\Ping\Pong;
use QuickPay\Repository\PingRepositoryInterface;
use GuzzleHttp\Client;

class PingRepository implements PingRepositoryInterface
{
    public function get(): ?QuickPayModel
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
        return null;
    }

    public function post(): ?QuickPayModel
    {
        return new \Exception('Method not implemented');
    }
}
