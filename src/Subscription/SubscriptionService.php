<?php
/**
 *
 *
 */
namespace QuickPay\Subscription;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use QuickPay\Subscription\Subscription;
use QuickPay\QuickPayService;

class SubscriptionService extends QuickPayService
{
    public function getAll(): Collection
    {
        $request_data = array_merge([], $this->withHeaders());
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
            $this->withHeaders()
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
            $this->withHeaders()
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
            $this->withHeaders()
        );
        $response = $this->client->patch(
            "subscriptions/$model->id",
            $this->withHeaders()
        );
        if ($response->getStatusCode() == 200) {
            $subscription = $this->get($model->id);

            return $subscription;
        }
    }

    public function authorize(array $order_data, $subscription_id)
    {
        $response = null;
        try {
            $request_data = array_merge(
                ['form_data' => $order_data],
                $this->withHeaders()
            );
            $response = $this->client->post(
                "subscriptions/$subscription_id/authorize",
                $request_data
            );
            if ($response->getStatusCode() == 202) {
                $body = $response->getBody();
                $json = $body->getContents();
                // TODO: Change to Subscription
                return $json;
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return new \Exception(
                sprintf('Call to authorize() failed! [%s]', $e->getMessage())
            );
        }
    }

    public function getPaymentLinkUrl(array $form_data, $subscription_id)
    {
        $request_data = array_merge(
            ['json' => json_encode($form_data), 'debug' => true],
            $this->withHeaders()
        );
        $response = $this->client->put(
            "subscriptions/$subscription_id/link",
            $request_data
        );
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $json = $body->getContents();
            dd($json);
        }
        throws\Exception('Problme wiht link');
    }
}
