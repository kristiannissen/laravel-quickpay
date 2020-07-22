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
		public function get($id): Model;
    public function create(array $order_data): Model;
    public function update(Model $model): Model;
    public function cancel(Model $model): bool;
}
