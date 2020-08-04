<?php

namespace QuickPay\Tests\Unit;

use QuickPay\Tests\TestCase;
use QuickPay\Subscription\SubscriptionService;

class SubscriptionTest extends TestCase
{
    protected $fake_data;

    public function setUp(): void
    {
        parent::setUp();

        $this->fake_data = [
            'order_id' => substr(md5(microtime()), rand(0, 26), 10),
            'currency' => 'DKK',
            'description' => 'Your Hello Kitty Subscription',
        ];
    }

    public function test_getall()
    {
        $service = new SubscriptionService();
        $collection = $service->getAll(['page_size' => 5]);
        $this->assertTrue($collection->count() >= 0);
    }

    public function test_create()
    {
        $service = new SubscriptionService();
        $subscription = $service->create($this->fake_data);

        $this->assertFalse(is_null($subscription->id));

        $this->markTestSkipped('Creates too many entries');
    }

    public function test_get_subscription()
    {
        $service = new SubscriptionService();
        $subscription = $service->get(196632144);
        $this->assertEquals(
            $this->fake_data['description'],
            $subscription->description
        );
    }

    public function test_update()
    {
        $service = new SubscriptionService();
        $sub = $service->getAll()->first();

        $subscription = $service->update(
            [
                'description' => 'Hello Pussy Subscription',
            ],
            $sub->id
        );

        $this->assertNotEquals($subscription->description, $sub->description);
    }

    public function test_authorize()
    {
        $service = new SubscriptionService();
        $sub = $service->create($this->fake_data);

        $subscription = $service->authorize(
            [
                'amount' => 2000,
                'acquirer' => 'clearhaus',
                'card' => [
                    'number' => '1000000000000008',
                    'expiration' => '2203',
                    'cvd' => '666',
                ],
            ],
            $sub->id
        );

        $this->assertTrue($subscription);
    }

    public function test_paymentlinkurl()
    {
        $service = new SubscriptionService();
        $url = $service->getPaymentLinkUrl(
            [
                'amount' => 2000,
            ],
            196632144
        );

        $this->assertIsString($url);
    }

    public function test_cancel()
    {
        $service = new SubscriptionService();
        $sub = $service->create($this->fake_data);

        $service->authorize(
            [
                'amount' => 2000,
                'acquirer' => 'clearhaus',
                'card' => [
                    'number' => '1000000000000008',
                    'expiration' => '2203',
                    'cvd' => '666',
                ],
            ],
            $sub->id
        );

        $subscription = $service->get($sub->id);

        $cancel = $service->cancel($subscription->id);

        $this->assertTrue($cancel);
    }
}
