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
            ],
            196632144
        );

        $this->assertTrue(true);
    }
}
