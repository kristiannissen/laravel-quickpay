<?php
/**
 *
 *
 */
namespace QuickPay\Account;

use QuickPay\Account\Contracts\AccountRepository;
use GuzzleHttp\Client;
use QuickPay\Account\Merchant;
use QuickPay\Account\CustomerAddress;

class AccountService implements AccountRepository
{
    protected $client;

    public function __construct()
    {
        $this->client = AccountService::getClient();
    }

    protected static function getClient()
    {
        return new Client([
            'base_uri' => 'https://api.quickpay.net/account',
        ]);
    }

    protected function buildHeaders(): array
    {
        return [
            'auth' => ['', config('quickpay.api_key')],
            'headers' => [
                'Accept-Version' => config('quickpay.version'),
                'Accept' => 'application/json',
            ],
        ];
    }

    public function get()
    {
        $response = $this->client->get('account', $this->buildHeaders());
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $json_array = (array) json_decode($body->getContents());
            $customer_address = new CustomerAddress(
                (array) $json_array['customer_address']
            );
            $merchant = new Merchant();
            $merchant->fill(
                $merchant->filterJson($merchant->getFillable(), $json_array)
            );
            $merchant->customerAddress($customer_address);

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

    public function patch()
    {
    }

    public function delete()
    {
    }
}
