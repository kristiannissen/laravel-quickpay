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
use Illuminate\Support\Env;

class Changelog extends QuickPayHttpClient implements ChangelogInterface
{
    public function get(): ?QuickPayModel
    {
        $response = $this->client->request('GET', 'changelog', [
            'headers' => [
                'Accept-Version' => 'v10',
                'Accept' => 'application/json',
            ],
            'auth' => [Env::get('QUICKPAY_USER'), Env::get('QUICKPAY_PWD')],
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
