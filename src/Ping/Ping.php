<?php

namespace QuickPay\Ping;

use QuickPay\Ping\Contracts\PingRepository;
use Illuminate\Support\Facades\Http;
use QuickPay\Ping\Message;

class Ping implements PingRepository
{
    public function get()
    {
        // TODO: Version should be coming from config
        $response = Http::withHeaders([
            'Accept-Version' => 'v10',
            'Accept' => 'application/json',
        ])->get('https://api.quickpay.net/ping');
        if ($response->ok()) {
            $json = json_decode($response->body());
            // Remove params from response
            unset($json->params);
            return new Message((array) $json);
        }
        throw new \Exception(
            sprintf('Call to ping returned [%s]', $response->status())
        );
    }
}
