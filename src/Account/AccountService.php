<?php
/**
 * Represents Merchant and implements Get and Update methods
 * https://learn.quickpay.net/tech-talk/api/services/#account
 * It does not offer Deleting the Merchant
 */
namespace QuickPay\Account;

use QuickPay\Account\Contracts\AccountRepository;
use GuzzleHttp\Client;
use QuickPay\Account\Merchant;
use QuickPay\Account\Address;
use Illuminate\Database\Eloquent\Model;
use QuickPay\QuickPayService;
use QuickPay\Account\AccountException;

class AccountService extends QuickPayService
{
    /**
     * Gets the Merchant data
     *
     * @return Model Merchant
     * @throws AccountException
     */
    public function get(): Model
    {
        try {
            $response = $this->client->get('account', $this->withHeaders());
            if ($response->getStatusCode() == 200) {
                $body = $response->getBody();
                $json_object = json_decode($body->getContents());
                $json_array = (array) $json_object;

                $merchant = new Merchant();
                $merchant->fill(
                    $merchant->filterJson($merchant->getFillable(), $json_array)
                );
                $merchant->customer_address = new Address(
                    array_merge(
                        ['address_type' => 'customer_address'],
                        (array) $json_array['customer_address']
                    )
                );
                if (is_null($json_array['billing_address']) == false) {
                    $merchant->billing_address = new Address(
                        array_merge(
                            ['address_type' => 'billing_address'],
                            (array) $json_array['billing_address']
                        )
                    );
                }
                return $merchant;
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $json = $this->getJson($response);

            throw new AccountException(
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
    /**
     * Updates Merchant data
     *
     * @param arra $form_params see
     * https://learn.quickpay.net/tech-talk/api/services for options
     * @return Model Merchant
     * @throws AccountException
     */
    public function update(array $form_params): Model
    {
        $data = array_merge(
            [
                'form_params' => $form_params,
            ],
            $this->withHeaders()
        );

        try {
            $response = $this->client->patch('account', $data);
            if ($response->getStatusCode() == 200) {
                $body = $response->getBody();
                $json_array = (array) json_decode($body->getContents());
                $merchant = new Merchant();
                $merchant->fill(
                    $merchant->filterJson($merchant->getFillable(), $json_array)
                );
                return $merchant;
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $json = $this->getJson($response);

            throw new AccountException(
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
