<?php
/**
 *
 */
namespace QuickPay\Account;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
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

    public function merchant()
    {
        $this->belongsTo('QuickPay\Account\Merchant');
    }
}
