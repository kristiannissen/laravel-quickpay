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
            // dd($json_array);

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
            $acquirer_setting = new AcquirerSetting();
            $acquirer_settings_array = (array) $json_array['acquirer_settings'];
            // $acquirer_names = array_keys($acquirer_array);

            foreach ($acquirer_settings_array as $key => $obj) {
                $acquirer_setting->acquirer = new Acquirer([
                    'name' => $key,
                    'active' => $obj->active,
                ]);
            }

            $merchant->acquirer_setting = $acquirer_setting;

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

    public function patch(Model $model)
    {
        $data = array_merge(
            [
                'json' => $model->toJson(),
                'debug' => true,
            ],
            $this->buildHeaders()
        );

        // $response = $this->client->patch('account', $data);
    }

    public function delete()
    {
        throw new \Exception('This method is not implemented');
    }
}
