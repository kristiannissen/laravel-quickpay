<?php
/**
 *
 */
namespace QuickPay\Account;

use Illuminate\Database\Eloquent\Model;

class Acquirer extends Model
{
    protected $fillable = ['name', 'active'];

    public function acquirersetting()
    {
        $this->belongsTo('QuickPay\Account\AcquirerSetting');
    }
}
