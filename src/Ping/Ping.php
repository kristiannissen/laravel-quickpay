<?php

namespace QuickPay\Ping;

use QuickPay\Ping\Contracts\PingRepository;
use QuickPay\Ping\Pong;
use QuickPay\QuickPayHttpClient;
use QuickPay\QuickPayModel;

class Ping extends QuickPayHttpClient implements PingRepository
{
    public function get(): ?QuickPayModel
    {
        $response = $this->client->request('GET', 'ping', [
            'headers' => [
                'Accept-Version' => 'v10',
                'Accept' => 'application/json',
            ],
            'debug' => true,
        ]);
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            return new Pong((array) json_decode($body->getContents()));
        }
        throw new \Exception(
            sprintf('Call to Ping returned [%d]', $response->getStatusCode())
        );
    }
}
