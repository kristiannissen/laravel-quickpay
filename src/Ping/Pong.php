<?php
/**
 *
 */
namespace QuickPay\Ping;

use QuickPay\QuickPayModel;

class Pong extends QuickPayModel
{
    protected $fillable = ['msg', 'scope', 'version', 'params'];
}
