<?php
/**
 *
 */
namespace QuickPay\Repository;

use QuickPay\QuickPayModel;
use QuickPay\Account\Account;
use GuzzleHttp\Client;

class AccountRepository implements AccountRepositoryInterface
{
    public function get(): ?QuickPayModel
    {
        $client = new Client([
            'base_uri' => 'https://api.quickpay.net',
        ]);
        $response = $client->request('GET', 'account', [
            'headers' => [
                'Accept-Version' => 'v10',
                'Accept' => 'application/json',
            ],
            'auth' => ['', ''],
            'debug' => true,
        ]);
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            return new Account((array) json_decode($body->getContents()));
        }
        return null;
    }
}
