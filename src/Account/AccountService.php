<?php
/**
 *
 *
 */
namespace QuickPay\Account;

use QuickPay\Account\Contracts\AccountRepository;
use GuzzleHttp\Client;
use QuickPay\Account\Merchant;
use QuickPay\Account\Address;
use Illuminate\Database\Eloquent\Model;
use QuickPay\Account\Acquirer;
use QuickPay\Account\AcquirerSetting;
use QuickPay\QuickPayService;

class AccountService extends QuickPayService
{

    public function get()
    {
        $response = $this->client->get('account', $this->withHeaders());
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $json_object = json_decode($body->getContents());
            $json_array = (array) $json_object;

            $merchant = new Merchant();
            $merchant->fill(
                $merchant->filterJson($merchant->getFillable(), $json_array)
            );
            $merchant->address = new Address(
                array_merge(
                    ['address_type' => 'customer_address'],
                    (array) $json_array['customer_address']
                )
            );
            return $merchant;
        }
        throw new \Exception(
            sprintf(
                '[%s] returned status code [%s]',
                $response->getStatusCode(),
                get_class($this)
            )
        );
    }

    public function update(Model $model)
    {
        $data = array_merge(
            [
                'form_params' => $model->toArray(),
            ],
            $this->withHeaders()
        );

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
        throw new \Exception(
            sprintf(
                'PATCH request returned status code [%s]',
                $response->getStatusCode()
            )
        );
    }

}
