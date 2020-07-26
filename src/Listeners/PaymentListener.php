<?php
/**
 *
 *
 */
namespace QuickPay\Listeners;

use QuickPay\Payment\Payment;
use QuickPay\Events\PaymentEvent;

class PaymentListener
{
    public function __construct()
    {
    }

    public function handle(PaymentEvent $event)
    {
    }

    public function failed(PaymentEvent $event, $exception)
    {
    }
}
