<?php

namespace QuickPay\Account\Contracts;

use Illuminate\Database\Eloquent\Model;

interface AccountRepository
{
    public function get();
    public function patch(Model $model);
    public function delete();
}
