<?php
/**
 *
 */
namespace QuickPay\Account;

use Illuminate\Database\Eloquent\Model;
use QuickPay\Concerns\Filterable;

class Address extends Model
{
    use Filterable;

    protected $fillable = [
        'name',
        'att',
        'street',
        'city',
        'zip_code',
        'region',
        'country_code',
        'vat_no',
    ];

    protected $append = ['address_type'];

    public function merchant()
    {
        $this->belongsTo('QuickPay\Account\Merchant')->withDefault();
    }
}
