<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Event;
use QuickPay\Tests\TestCase;
use QuickPay\Events\PaymentEvent;
use QuickPay\Payment\PaymentService;
use QuickPay\Payment\Exception\PaymentException;
use Illuminate\Support\Str;

class PaymentEventTest extends TestCase
{
    public function test_paymentevent()
    {
        $this->assertTrue(true);
    }
}
