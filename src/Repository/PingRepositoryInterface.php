<?php
/**
 *
 */
namespace QuickPay\Repository;

use QuickPay\QuickPayModel;

interface PingRepositoryInterface
{
    public function get(): ?QuickPayModel;
    public function post(): ?QuickPayModel;
}
