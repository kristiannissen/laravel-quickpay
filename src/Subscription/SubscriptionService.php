<?php
/**
 *
 *
 */
namespace QuickPay\Subscription;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use QuickPay\Subscription\Contracts\SubscriptionRepository;
use QuickPay\Subscription\Subscription;
use Illuminate\Support\Facades\Log;

class SubscriptionService implements SubscriptionRepository {
    protected $client;

    public function __construct()
    {
        $this->client = SubscriptionService::getClient();
    }

    protected static function getClient()
    {
        return new Client([
            'base_uri' => 'https://api.quickpay.net',
        ]);
    }

    protected function buildHeaders(): array
    {
        return [
            'auth' => ['', config('quickpay.api_key')],
            'headers' => [
                'Accept-Version' => config('quickpay.version'),
                'Accept' => 'application/json',
            ],
        ];
    }

    public function getAll(): Collection {
    }

    public function create(array $order_data): Model {
			$request_data = array_merge(
				['form_params' => $order_data],
				$this->buildHeaders()
			);
			$response = $this->client->post('subscriptions', $request_data);
			if ($response->getStatusCode() == 201) {
				$body = $response->getBody();
				$json_array = (array) json_decode($body->getContents());

				return new Subscription($json_array);
			}
			throw new \Exception(sprintf(
				'POST call to subscriptions from [%s] returned [%s]',
				get_class($this),
				$response->getStatusCode()
			));
    }

    public function update(array $order_data): Model {}

    public function cancel(Model $model): bool {}
}
