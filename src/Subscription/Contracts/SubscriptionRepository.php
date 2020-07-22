<?php
/**
 *
 *
 */
namespace QuickPay\Subscription\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface SubscriptionRepository
{
    public function getAll(): Collection;
    public function create(array $order_data): Model;
    public function update(array $order_data): Model;
    public function cancel(Model $model): bool;
}
