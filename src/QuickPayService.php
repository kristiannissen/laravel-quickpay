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
