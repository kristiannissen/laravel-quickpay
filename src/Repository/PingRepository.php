<?php
/**
 *
 */
namespace QuickPay\Repository;

use QuickPay\QuickPayModel;
use QuickPay\Ping\Pong;
use QuickPay\Repository\RepositoryInterface;
use QuickPay\QuickPayHttpClient;

class PingRepository extends QuickPayHttpClient implements RepositoryInterface
{
    public function get(): ?QuickPayModel
    {
        $response = $this->client->request('GET', 'ping', [
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

    public function getByKey($key = null): ?QuickPayModel
    {
        throw new \Exception('Method not implemented');
    }

    public function put(QuickPayModel $model): ?QuickPayModel
    {
        throw new \Exception('Method not implemented');
    }
}
