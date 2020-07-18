<?php
/**
 *
 *
 */
namespace QuickPay\Changelog;

use QuickPay\Changelog\Contracts\ChangelogInterface;
use GuzzleHttp\Client;
use QuickPay\Changelog\Changes;

class Changelog implements ChangelogInterface
{
    public function get()
    {
        $client = new Client([
            'base_uri' => 'https://api.quickpay.net',
        ]);
        $response = $client->get('changelog', [
            'auth' => [
                '',
                config('quickpay.api_key'),
            ],
            'headers' => [
                'Accept-Version' => config('quickpay.version'),
                'Accept' => 'application/json',
            ]
        ]);
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            return new Changes((array) json_decode($body->getContents()));
        }
        throw new \Exception(sprintf(
            '[%s] returned status code [%s]',
            get_class($this),
            $response->getStatusCode()
        ));
    }
}
