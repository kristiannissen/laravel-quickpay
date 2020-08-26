<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Event;
use QuickPay\Tests\TestCase;
use QuickPay\Events\PaymentEvent;
use QuickPay\Payment\PaymentService;
use QuickPay\Payment\PaymentException;
use Illuminate\Support\Str;

class PaymentEventTest extends TestCase
{
    public function test_paymentevent()
    {
        Event::fake();

        $service = new PaymentService();
        $payment = $service->create([
            'order_id' => Str::random(10),
            'amount' => 1000,
            'currency' => 'DKK',
        ]);

        Event::assertDispatched(function (PaymentEvent $event) use ($payment) {
            return $event->payment->id === $payment->id;
        });
    }
}
