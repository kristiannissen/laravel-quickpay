<?php

namespace QuickPay\Payment;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'id',
        'merchant_id',
        'order_id',
        'accepted',
        'type',
        'text_on_statement',
        'branding_id',
        'variables',
        'currency',
        'state',
        'metadata',
        'link',
        'shipping_address',
        'invoice_address',
        'basket',
        'shipping',
        'operations',
        'test_mode',
        'acquirer',
        'facilitator',
        'created_at',
        'updated_at',
        'retented_at',
        'balance',
        'fee',
        'subscription_id',
        'deadline_at',
    ];
}
