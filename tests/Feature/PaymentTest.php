<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Event;
use QuickPay\Tests\TestCase;
use QuickPay\Events\PaymentEvent;

class PaymentTest extends TestCase
{
    public function test_paymentevent()
    {
        $this->assertTrue(true);
    }
}
