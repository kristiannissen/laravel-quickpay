<?php
/**
 */
namespace QuickPay\Acquirer;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use QuickPay\Acquirer\Acquirer;
use QuickPay\QuickPayService;

class AcquirerService extends QuickPayService
{
    public function getAll(): Collection
    {
        $response = $this->client->get('acquirers', $this->withHeaders());
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $json = json_decode($body->getContents(), true);
            $acquirers = array_map(function ($key) use ($json) {
                return new Acquirer([
                    'name' => $key,
                    'settings' => $json[$key],
                ]);
            }, array_keys($json));
            return collect($acquirers);
        }
        throw new \Exception(
            sprintf(
                'GET returned [%s] when called in getAll()',
                $response->getStatusCode()
            )
        );
    }
}
