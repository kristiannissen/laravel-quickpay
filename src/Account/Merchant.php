<?php
/**
 *
 *
 */
namespace QuickPay\Account;

use Illuminate\Database\Eloquent\Model;
use QuickPay\Concerns\Filterable;

class Merchant extends Model
{
    use Filterable;

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
    ];

    protected $append = ['address_type'];

    public function customer_address()
    {
        return $this->hasOne('QuickPay\Account\Address');
    }

    public function delivery_address()
    {
        return $this->hasOne('QuickPay\Account\Address');
    }
}
