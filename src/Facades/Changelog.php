<?php
/**
 *
 *
 */
namespace QuickPay\Facades;

use Illuminate\Support\Facades\Facade;

class Changelog extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'changelog';
    }
}
