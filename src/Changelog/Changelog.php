<?php
/**
 *
 *
 */
namespace QuickPay\Changelog;

use GuzzleHttp\Client;
use QuickPay\QuickPayHttpClient;

class Changelog extends QuickPayHttpClient
{
    public $attributes = [
        'changes' => '',
    ];
    public function __construct(array $attributes = [])
    {
    }

    /**
     *
     */
    public function getUri(): string
    {
        return 'changelog';
    }
}
