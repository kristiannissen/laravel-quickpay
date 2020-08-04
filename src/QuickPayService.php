<?php
/**
 *
 */
namespace QuickPay;

use GuzzleHttp\Client;

abstract class QuickPayService
{
    protected $client;

    public function __construct()
    {
        $this->client = $this->getClient();
    }

    public function validateParams(array $rules = [], array $data_types = [])
    {
        $rule_keys = array_keys($rules);
        $data_keys = array_keys($data_types);
        foreach ($rule_keys as $rule_key) {
            list($name, $type) = explode(':', $rule_key);
            if (in_array($name, $data_keys) == false) {
                throw new \InvalidArgumentException(
                    sprintf('The parameter %s is missing!', $rules[$rule_key])
                );
            } elseif (gettype($data_types[$name]) !== $type) {
                throw new \UnexpectedValueException(
                    sprintf(
                        'The parameter %s should be of type %s but %s received!',
                        strtoupper($name),
                        strtoupper($type),
                        strtoupper(gettype($data_types[$name]))
                    )
                );
            }
        }
    }

    public function getClient()
    {
        return new Client([
            'base_uri' => 'https://api.quickpay.net',
        ]);
    }

    public function withHeaders()
    {
        return [
            'auth' => ['', config('quickpay.api_key')],
            'headers' => [
                'Accept-Version' => config('quickpay.version'),
                'Accept' => 'application/json',
            ],
        ];
    }

    public function getJson($response)
    {
        $body = $response->getBody();
        return json_decode($body->getContents());
    }

    public function errorsToString($json)
    {
        $str = '';
        $errors = (array) $json->errors;
        foreach (array_keys($errors) as $key) {
            $str .= $key . ' - ';
            foreach ($errors[$key] as $val) {
                $str .= $val;
            }
        }

        return $str;
    }
}
