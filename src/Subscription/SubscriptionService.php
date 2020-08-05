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
use QuickPay\Subscription\SubscriptionException;
use QuickPay\QuickPayService;
use QuickPay\Events\SubscriptionEvent;

class SubscriptionService extends QuickPayService
{
    private static $required_data_types = [
        'create' => [
            'order_id:string' =>
                'Unique order id(must be between 4-20 characters)',
            'currency:string' => 'Currency eg DKK',
            'description:string' => 'Subscription description',
        ],
        'get' => [
            'id:integer' => 'Subscription id',
        ],
        'authorize' => [
            'id:integer' => 'Subscription id',
            'amount:integer' => 'Amount',
        ],
        'update' => [
            'id:integer' => 'Subscription id',
        ],
        'getPaymentLinkUrl' => [
            'id:integer' => 'Subscription id',
            'amount:integer' => 'Amount to authorize',
        ],
        'cancel' => [
            'id:integer' => 'Subscription id',
        ],
        'recurring' => [
            'id:integer' => 'Subscription id',
            'order_id:string' =>
                'Unique order id(must be between 4-20 characters)',
        ],
    ];
    /**
     * Returns all subscriptions
     *
     * @param array $query paramters
     * @return Collection
     * @throws SubscriptionException
     */
    public function getAll(array $query_params = []): Collection
    {
        // Here we merge the query parameters with the prepared
        // headers before they are passed to the client
        $request_data = array_merge(
            [
                'query' => $query_params,
            ],
            $this->withHeaders()
        );

        try {
            // Make the GET request to the endpoint
            $response = $this->client->get('subscriptions', $request_data);
            // Unless the status code equals 200 do we do anything
            if ($response->getStatusCode() == 200) {
                $subscriptions = [];
                // Get the JSON data from the response object
                $json_resp = $this->getJson($response);
                // Iterate over all JSON objects, populate the model
                // and push it onto the array
                foreach ($json_resp as $sub) {
                    $subscription = new Subscription((array) $sub);
                    array_push($subscriptions, $subscription);
                }
                // Turn the array into a Laravel Collection and return it
                return collect($subscriptions);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Get the request response object from the exception
            $response = $e->getResponse();
            // Get the JSON data from the response
            $json = $this->getJson($response);
            // Build the exception to throw
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
     * @param array $form_params
     * @return model Subscription
     * @throws SubscriptionException
     */
    public function create(array $form_params): Model
    {
        $this->validateParams(
            self::$required_data_types[__FUNCTION__],
            $form_params
        );

        $request_data = array_merge(
            ['form_params' => $form_params],
            $this->withHeaders()
        );

        try {
            $response = $this->client->post('subscriptions', $request_data);
            if ($response->getStatusCode() == 201) {
                $json_array = (array) $this->getJson($response);
                $subscription = new Subscription();
                $subscription->fill(
                    $subscription->filterJson(
                        $subscription->getFillable(),
                        $json_array
                    )
                );
                $subscription = new Subscription($json_array);
                event(new SubscriptionEvent($subscription));

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
     * Get a single subscription
     *
     * @param number $subscription_id
     * @return model Subscription
     * @throws SubscriptionException
     */
    public function get($subscription_id): Model
    {
        $this->validateParams(self::$required_data_types[__FUNCTION__], [
            'id' => $subscription_id,
        ]);

        try {
            $response = $this->client->get(
                "subscriptions/$subscription_id",
                $this->withHeaders()
            );
            if ($response->getStatusCode() == 200) {
                $json = $this->getJson($response);
                $subscription = new Subscription();
                $subscription->fill(
                    $subscription->filterJson(
                        $subscription->getFillable(),
                        (array) $json
                    )
                );
                event(new SubscriptionEvent($subscription));

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
        $this->validateParams(
            self::$required_data_types[__FUNCTION__],
            array_merge(['id' => $subscription_id], $form_params)
        );

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
                event(new SubscriptionEvent($subscription));

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
     * Authorize an existing subscription
     *
     * @param array $form_params
     * @param number $subscription_id
     * @return bool
     * @throws SubscriptionException
     */
    public function authorize(array $form_params, $subscription_id): void
    {
        $this->validateParams(
            self::$required_data_types[__FUNCTION__],
            array_merge(['id' => $subscription_id], $form_params)
        );

        try {
            $request_data = array_merge(
                ['form_params' => $form_params],
                $this->withHeaders()
            );
            $response = $this->client->post(
                "subscriptions/$subscription_id/authorize",
                $request_data
            );
            if ($response->getStatusCode() == 202) {
                $subscription = $this->get($subscription_id);
                event(new SubscriptionEvent($subscription));
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
     * Generate a payment window with a card form
     *
     * @param array $form_params
     * @param number $subscription_id
     * @return string
     * @throws SubscriptionException
     */
    public function getPaymentLinkUrl(array $form_params, $subscription_id)
    {
        $this->validateParams(
            self::$required_data_types[__FUNCTION__],
            array_merge(['id' => $subscription_id], $form_params)
        );

        $request_data = array_merge(
            ['form_params' => $form_params],
            $this->withHeaders()
        );

        try {
            $response = $this->client->put(
                "subscriptions/$subscription_id/link",
                $request_data
            );
            if ($response->getStatusCode() == 200) {
                $json = $this->getJson($response);
                $subscription = $this->get($subscription_id);
                event(new SubscriptionEvent($subscription));

                return $json->url;
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
     * Cancels a subscription
     *
     * @param number $subscription_id
     * @return bool
     * @throws SubscriptionException
     */
    public function cancel($subscription_id): void
    {
        $this->validateParams(self::$required_data_types[__FUNCTION__], [
            'id' => $subscription_id,
        ]);

        $request_data = array_merge([], $this->withHeaders());

        try {
            $response = $this->client->post(
                "subscriptions/$subscription_id/cancel",
                $request_data
            );
            if ($response->getStatusCode() == 202) {
                $subscription = $this->get($subscription_id);
                event(new SubscriptionEvent($subscription));
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
     * Creates a recurring payment
     *
     * @param array $form_params
     * @return void
     * @throws SubscriptionException
     */
    public function recurring(array $form_params, $subscription_id): void
    {
        $this->validateParams(
            self::$required_data_types[__FUNCTION__],
            array_merge(['id' => $subscription_id], $form_params)
        );

        $request_data = array_merge(
            [
                'form_params' => $form_params,
            ],
            $this->withHeaders()
        );

        try {
            $response = $this->client->post(
                "subscriptions/$subscription_id/recurring",
                $request_data
            );
            $subscription = $this->get($subscription_id);
            event(new SubscriptionEvent($subscription));
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
}
