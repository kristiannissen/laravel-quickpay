<?php
/**
 */
namespace QuickPay\Subscription;

use Illuminate\Database\Eloquent\Model;
use QuickPay\Concerns\Filterable;

class Subscription extends Model
{
    use Filterable;

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
        'description',
        'group_ids',
        'deadline_at',
    ];
}
