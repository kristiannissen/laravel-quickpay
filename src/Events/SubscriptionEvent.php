<?php
/*
 *
 */
namespace QuickPay\Events;

use QuickPay\Subscription\Subscription;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscriptionEvent
{
    use Dispatchable, SerializesModels;

    public $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }
}
