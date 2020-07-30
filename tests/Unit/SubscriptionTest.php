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

    public function test_get_all_subscriptions()
    {
        $service = new SubscriptionService();
        $collection = $service->getAll();
        $this->assertTrue($collection->count() >= 0);
    }

    public function test_create_subscription()
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

    public function test_update_subscription()
    {
        $service = new SubscriptionService();
        $subscription = $service->get(196632144);
        $subscription->description = 'Hello Kitty 2.0 is rad!';
        $updated_sub = $service->update($subscription);

        $this->assertEquals(
            $subscription->description,
            $updated_sub->description
        );
    }

    public function test_authorize_subscription()
    {
        $service = new SubscriptionService();
        $subscription = $service->authorize(
            [
                'amount' => 2000,
                'acquirer' => 'clearhaus',
                'card' => [
                    'number' => '1000000000000008',
                    'expiration' => '03/22',
                    'cvd' => '666',
                ],
            ],
            196875423
        );

        $this->assertTrue(true);
    }

    public function test_get_paymentlinkurl()
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

    public function test_cancel_subscription_wrong_state()
    {
        $service = new SubscriptionService();
        $subscription = $service->getAll()->first();
        $cancel = $service->cancel($subscription->id);

        $this->assertEquals(400, $cancel->getCode());
    }
}
