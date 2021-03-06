<?php

namespace QuickPay\Tests\Unit;

use QuickPay\Tests\TestCase;
use QuickPay\Payment\PaymentService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;

class PaymentTest extends TestCase
{
    protected $fake_data;

    public function setUp(): void
    {
        parent::setUp();

        $this->fake_data = [
            'order_id' => Str::random(10),
            'currency' => 'DKK',
        ];
    }
    /**
     */
    public function test_create_payment()
    {
        $service = new PaymentService();
        $payment = $service->create($this->fake_data);

        $this->assertFalse(is_null($payment->id));
        // $this->markTestSkipped('Creates too many entries');
    }
    /**
     */
    public function test_get_all()
    {
        $service = new PaymentService();
        $payments = $service->getAll();

        $this->assertTrue($payments->count() >= 0);
    }
    /**
     *
     */
    public function test_get_paymentlink_url()
    {
        $service = new PaymentService();
        $payment = $service->getAll()->first();
        $url = $service->getPaymentLinkUrl(['amount' => 2000], $payment->id);

        $this->assertIsString($url);
    }
    /**
     */
    public function test_authorize()
    {
        $service = new PaymentService();
        $payment = $service->getAll()->first();
        $data = $service->authorize(
            [
                'amount' => 1000,
                'acquirer' => 'clearhaus',
                'card' => [
                    'number' => '1000000000000008',
                    'expiration' => '2203',
                    'cvd' => '666',
                ],
            ],
            $payment->id
        );

        $this->assertEquals($data->state, 'pending');
    }

    public function test_capture()
    {
        $service = new PaymentService();
        $payment = $service->getAll(['state' => 'new'])->first();
        $data = $service->capture(
            [
                'amount' => 10,
            ],
            $payment->id
        );

        $this->assertEquals($data->state, 'pending');
    }

    public function test_refund()
    {
        $service = new PaymentService();
        $payment = $service->getAll(['state' => 'processed'])->first();
        $data = $service->refund(['amount' => 1], $payment->id);

        $this->assertEquals($data->state, 'pending');
    }
}
