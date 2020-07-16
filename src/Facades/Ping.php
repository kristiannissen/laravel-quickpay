<?php
/**
 *
 *
 */
namespace QuickPay\Facades;

use Illuminate\Support\Facades\Facade;

class Ping extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ping';
    }
}
