<?php

namespace QuickPay\Tests\Unit;

use QuickPay\Tests\TestCase;
use QuickPay\Payment\PaymentService;
use Illuminate\Support\Str;

class PaymentTest extends TestCase
{
    protected $fake_data;

    public function setUp(): void
    {
        parent::setUp();

        $this->fake_data = [
            'order_id' => Str::random(10),
            'currency' => 'DKK',
            'basket' => [
                [
                    'qty' => 1,
                    'item_no' => 42,
                    'item_name' => 'Hello Kitty',
                    'item_price' => 2000,
                    'vat_rate' => 25,
                ],
                [
                    'qty' => 1,
                    'item_no' => 666,
                    'item_name' => 'Hello Pussy',
                    'item_price' => 3000,
                    'vat_rate' => 25,
                ],
            ],
        ];
    }

    public function test_create_payment()
    {
        $service = new PaymentService();
        $payment = $service->create($this->fake_data);

        $this->assertFalse(is_null($payment->id));
    }
}
