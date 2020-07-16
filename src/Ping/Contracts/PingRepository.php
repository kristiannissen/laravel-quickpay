<?php

namespace QuickPay\Ping\Contracts;

use Illuminate\Database\Eloquent\Model;

interface PingRepository
{
    public function get();
}
