<?php

namespace QuickPay\Ping\Contracts;

use QuickPay\QuickPayModel;

interface PingRepository
{
    public function get(): ?QuickPayModel;
}
