<?php
/**
 *
 *
 */
namespace QuickPay\Account;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $fillable = [
        'id',
        'type',
        'callback_url',
        'contact_email',
        'shop_name',
        'shop_url',
        'shop_urls',
        'shopsystem',
        'timezone',
        'locale',
        'default_branding_id',
        'default_payment_language',
        'default_payment_methods',
        'default_text_on_statement',
        'allow_test_transactions',
        'autofee',
        'default_branding_config',
        'customer_address',
        'billing_address',
        'acquirer_settings',
        'integration_settings',
        'pci',
        'reseller',
        'created_at',
        'suspended_at',
        'logging_stops_at',
    ];
}
