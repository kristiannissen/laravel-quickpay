<?php

namespace QuickPay\Repository;

use QuickPay\QuickPayModel;

interface RepositoryInterface
{
    public function get(): ?QuickPayModel;
    public function getByKey($key = null): ?QuickPayModel;
    public function put(QuickPayModel $model): ?QuickPayModel;
}
