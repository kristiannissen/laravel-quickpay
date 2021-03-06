<?php
/**
 * This class represents the Acquirer service
 * it returns a collection of available Acquirers and their individual settings
 *
 */
namespace QuickPay\Acquirer;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use QuickPay\Acquirer\Acquirer;
use QuickPay\QuickPayService;
use QuickPay\Acquirer\AcquirerException;

class AcquirerService extends QuickPayService
{
    /**
     * Request all Acquirers
     *
     * @return Collection $collection
     * @throws AcquirerException
     */
    public function getAll(): Collection
    {
        try {
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
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $json = $this->getJson($response);

            throw new PaymentException(
                sprintf(
                    '%s threw an exception - %s check %s',
                    ucfirst(__FUNCTION__),
                    $json->message,
                    $this->errorsToString($json)
                ),
                $response->getStatusCode(),
                null
            );
        }
    }
}
