<?php
/**
 *
 *
 */
namespace QuickPay\Subscription;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use QuickPay\Subscription\Contracts\SubscriptionRepository;
use QuickPay\Subscription\Subscription;

class SubscriptionService implements SubscriptionRepository
{
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

    public function getAll(): Collection
    {
        $request_data = array_merge([], $this->buildHeaders());
        $response = $this->client->get('subscriptions', $request_data);
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $subscriptions = [];
            $json_resp = json_decode($body->getContents());

            foreach ($json_resp as $sub) {
                $subscription = new Subscription((array) json_encode($sub));
                array_push($subscriptions, $subscription);
            }

            return collect($subscriptions);
        }
        throw new \Exception(
            sprintf(
                'GET call to subscriptions from [%s] returned [%s]',
                get_class($this),
                $response->getStatusCode()
            )
        );
    }

    public function create(array $order_data): Model
    {
        $request_data = array_merge(
            ['form_params' => $order_data],
            $this->buildHeaders()
        );
        $response = $this->client->post('subscriptions', $request_data);
        if ($response->getStatusCode() == 201) {
            $body = $response->getBody();
            $json_array = (array) json_decode($body->getContents());
            $subscription = new Subscription();
            $subscription->fill(
                $subscription->filterJson(
                    $subscription->getFillable(),
                    $json_array
                )
            );

            return new Subscription($json_array);
        }
        throw new \Exception(
            sprintf(
                'POST call to subscriptions from [%s] returned [%s]',
                get_class($this),
                $response->getStatusCode()
            )
        );
    }

    public function get($id): Model
    {
        $response = $this->client->get(
            "subscriptions/$id",
            $this->buildHeaders()
        );
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $json = json_decode($body->getContents());
            $subscription = new Subscription();
            $subscription->fill(
                $subscription->filterJson(
                    $subscription->getFillable(),
                    (array) $json
                )
            );

            return $subscription;
        }
        throw new \Exception(
            sprintf(
                'GET request to subscriptions from [%s] returned [%s]',
                get_class($this),
                $response->getStatusCode()
            )
        );
    }

    public function update(Model $model): Model
    {
        $request_data = array_merge(
            ['form_data' => $model->toFormArray(['id'])],
            $this->buildHeaders()
        );
        $response = $this->client->patch(
            "subscriptions/$model->id",
            $this->buildHeaders()
        );
        if ($response->getStatusCode() == 200) {
            $subscription = $this->get($model->id);

            return $subscription;
        }
    }

    public function authorize(array $order_data, $subscription_id)
    {
        $request_data = array_merge(
            ['form_data' => $order_data],
            $this->buildHeaders()
        );
        $response = $this->client->post(
            "subscriptions/$subscription_id/authorize",
            $request_data
        );
        if ($response->getStatusCode() == 202) {
            $body = $response->getBody();
            $json = $body->getContents();
            dd($json);
        }
        throws\Exception(
            sprintf(
                'Call to authorize() failed! Endpoint returned [%s]',
                $response->getStatusCode()
            )
        );
    }
}
