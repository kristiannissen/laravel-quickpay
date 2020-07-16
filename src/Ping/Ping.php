<?php

namespace QuickPay\Ping;

use QuickPay\Ping\Contracts\PingRepository;
use QuickPay\Ping\Pong;
use Illuminate\Support\Facades\Http;
use QuickPay\QuickPayModel;
use Illuminate\Support\Env;

class Ping
{
    public function get()
    {
        dd(config('quickpay'));
        $response = Http::withHeaders([
            'Accept-Version' => 'v10',
            'Accept' => 'application/json',
        ])->get('https://api.quickpay.net/ping');
        return $response->body();
    }
}
