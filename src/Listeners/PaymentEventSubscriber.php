<?php
/**
 *
 */
namespace QuickPay\Listeners;

class PaymentEventSubscriber
{
    public function handlePaymentCreated($event)
    {
        dd($event);
    }

    public function subscribe($events)
    {
        $events->listen(
            'QuickPay\Events\PaymentEvent',
            'QuickPay\Listeners\PaymentEventSubscriber@handlePaymentCreated'
        );
    }
}
