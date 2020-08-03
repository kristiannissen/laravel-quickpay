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
use QuickPay\Subscription\Exception\SubscriptionException;
use QuickPay\QuickPayService;

class SubscriptionService extends QuickPayService
{
    /**
     * Returns all subscriptions
     *
     * @param array $query paramters
     * @return Collection
     * @throws SubscriptionException
     */
    public function getAll(array $query_params = []): Collection
    {
        $request_data = array_merge(
            [
                'query' => $query_params,
            ],
            $this->withHeaders()
        );

        try {
            $response = $this->client->get('subscriptions', $request_data);
            if ($response->getStatusCode() == 200) {
                $body = $response->getBody();
                $subscriptions = [];
                $json_resp = json_decode($body->getContents());

                foreach ($json_resp as $sub) {
                    $subscription = new Subscription((array) $sub);
                    array_push($subscriptions, $subscription);
                }

                return collect($subscriptions);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $json = $this->getJson($response);

            throw new SubscriptionException(
                sprintf(
                    '%s threw an exception - %s check %s',
                    ucfirst(__FUNCTION__),
                    $json->message,
                    $this->errorsToString($json)
                ),
                $response->getStatusCode(),
                null
            );
        }
    }
    /**
     * Creates a new subscription
     *
     * @param array $order_data, order_id, currency, description are required
     * @return model Subscription
     * @throws SubscriptionException
     */
    public function create(array $order_data): Model
    {
        $request_data = array_merge(
            ['form_params' => $order_data],
            $this->withHeaders()
        );

        try {
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
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $json = $this->getJson($response);

            throw new SubscriptionException(
                sprintf(
                    '%s threw an exception - %s check %s',
                    ucfirst(__FUNCTION__),
                    $json->message,
                    $this->errorsToString($json)
                ),
                $response->getStatusCode(),
                null
            );
        }
    }
    /**
     * Get a single subscription
     *
     * @param number $id
     * @return model Subscription
     * @throws SubscriptionException
     */
    public function get($id): Model
    {
        try {
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
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $json = $this->getJson($response);

            throw new SubscriptionException(
                sprintf(
                    '%s threw an exception - %s check %s',
                    ucfirst(__FUNCTION__),
                    $json->message,
                    $this->errorsToString($json)
                ),
                $response->getStatusCode(),
                null
            );
        }
    }
    /**
     * Updates an existing subscription
     *
     * @param array $form_params
     * @param number $subscription_id
     * @return model Subscription
     * @throws SubscriptionException
     */
    public function update(array $form_params = [], $subscription_id): Model
    {
        $request_data = array_merge(
            ['form_params' => $form_params],
            $this->withHeaders()
        );
        try {
            $response = $this->client->patch(
                "subscriptions/$subscription_id",
                $request_data
            );
            if ($response->getStatusCode() == 200) {
                $subscription = $this->get($subscription_id);

                return $subscription;
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $json = $this->getJson($response);

            throw new SubscriptionException(
                sprintf(
                    '%s threw an exception - %s check %s',
                    ucfirst(__FUNCTION__),
                    $json->message,
                    $this->errorsToString($json)
                ),
                $response->getStatusCode(),
                null
            );
        }
    }
    /**
     * Authorizes an existing subscription
     *
     * @param array $order_data
     * @param number $subscription_id
     * @return bool
     * @throws SubscriptionException
     */
    public function authorize(array $order_data, $subscription_id): bool
    {
        try {
            $request_data = array_merge(
                ['form_params' => $order_data],
                $this->withHeaders()
            );
            $response = $this->client->post(
                "subscriptions/$subscription_id/authorize",
                $request_data
            );

            return $response->getStatusCode() == 202 ? true : false;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $json = $this->getJson($response);

            throw new SubscriptionException(
                sprintf(
                    '%s threw an exception - %s check %s',
                    ucfirst(__FUNCTION__),
                    $json->message,
                    $this->errorsToString($json)
                ),
                $response->getStatusCode(),
                null
            );
        }
    }

    public function getPaymentLinkUrl(array $form_data, $subscription_id)
    {
        $request_data = array_merge(
            ['form_params' => $form_data],
            $this->withHeaders()
        );
        $response = $this->client->put(
            "subscriptions/$subscription_id/link",
            $request_data
        );
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $json = (array) json_decode($body->getContents());

            return $json['url'];
        }
        throws\Exception('Problem wiht link');
    }

    public function cancel($subscription_id)
    {
        $request_data = array_merge([], $this->withHeaders());
        try {
            $response = $this->client->post(
                "subscriptions/$subscription_id/cancel",
                $request_data
            );
            if ($response->getStatusCode() == 202) {
                return true;
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $body = $response->getBody();
            $json = json_decode($body);

            return new SubscriptionException(
                $json->message,
                $response->getStatusCode(),
                $e
            );
        }
    }
}
