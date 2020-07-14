<?php
/**
 *
 *
 */
namespace QuickPay\Changelog;

use QuickPay\Changelog\Contracts\ChangelogInterface;
use QuickPay\QuickPayModel;
use QuickPay\QuickPayHttpClient;
use QuickPay\Changelog\Changes;

class Changelog extends QuickPayHttpClient implements ChangelogInterface
{
    public function get(): ?QuickPayModel
    {
        $response = $this->client->request('GET', 'changelog', [
            'headers' => [
                'Accept-Version' => 'v10',
                'Accept' => 'application/json',
            ],
            'auth' => [],
        ]);
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            return new Changes((array) json_decode($body));
        }
        throw new \Exception(
            sprintf(
                'Call to changelog returned [%d]',
                $response->getStatusCode()
            )
        );
    }
}
