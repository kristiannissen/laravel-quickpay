<?php
/**
 *
 */
namespace QuickPay\Account;

use Illuminate\Database\Eloquent\Model;

class AcquirerSetting extends Model
{
    protected $fillable = [];

    public function merchant()
    {
        $this->belongsTo('QuickPay\Account\Merchant');
    }

    public function acquirers()
    {
        $this->hasMany('QuickPay\Account\Acquirer');
    }
}
