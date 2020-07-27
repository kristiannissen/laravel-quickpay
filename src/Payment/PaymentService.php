<?php
/**
 *
 *
 */
namespace QuickPay\Payment;

use QuickPay\QuickPayService;
use QuickPay\Payment\Exception\PaymentException;
use QuickPay\Payment\Payment;
use QuickPay\Events\PaymentEvent;
use Illuminate\Support\Collection;

class PaymentService extends QuickPayService
{
    /**
     * This method take an array containing the mandatory data
     * in order to create a new payment
     *
     * @param array $form_params;
     * @return model
     * @throws PaymentException
     */
    public function create(array $form_params): Payment
    {
        $request_data = array_merge(
            ['form_params' => $form_params],
            $this->withHeaders()
        );

        try {
            $response = $this->client->post('payments', $request_data);
            if ($response->getStatusCode() == 201) {
                $json = $this->getJson($response);

                $payment = new Payment((array) $json);
                PaymentEvent::dispatch($payment);
                return $payment;
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // TODO: Move thid to the abstract class, use __FUNCTION__ to get
            // the current method throwing the exception
            $response = $e->getResponse();
            $json = $this->getJson($response);

            PaymentEvent::dispatch(new Payment());

            throw new PaymentException(
                sprintf(
                    'Create threw an exception - %s check %s',
                    $json->message,
                    $this->errorsToString($json)
                ),
                $response->getStatusCode(),
                null
            );
        }
    }
    /**
     * This method returns all matching payments
     *
     * @param array $query_params (check
     * https://learn.quickpay.net/tech-talk/api/services/#services)
     * @return collection
     * @throws PaymentException
     */
    public function getAll(array $query_params = []): Collection
    {
        $request_data = array_merge(
            ['query' => $query_params],
            $this->withHeaders()
        );
        try {
            $response = $this->client->get('payments', $request_data);
            if ($response->getStatusCode() == 200) {
                $json = $this->getJson($response);
                $payments = [];
                foreach ($json as $obj) {
                    array_push($payments, new Payment((array) $obj));
                }
                return collect($payments);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $json = $this->getJson($response);
            throw new PaymentException(
                sprintf(
                    'GetAll threw an exception - %s check %s',
                    $json->message,
                    $this->errorsToString($json)
                ),
                $response->getStatusCode(),
                null
            );
        }
    }
    /**
     * @param array $form_params
     * @param integer $payment_id
     * @return string
     * @throws PaymentException
     */
    public function getPaymentLinkURL(array $form_params = [], $payment_id)
    {
        $request_data = array_merge(
            ['form_params' => $form_params],
            $this->withHeaders()
        );
        try {
            $response = $this->client->put(
                "payments/$payment_id/link",
                $request_data
            );
            if ($response->getStatusCode() == 200) {
                $json = $this->getJson($response);
                return $json->url;
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $json = $this->getJson($response);
            throw new PaymentException(
                sprintf(
                    'GetPaymentLinkUrl threw an exception - %s check %s',
                    $json->message,
                    $this->errorsToString($json)
                ),
                $response->getStatusCode(),
                null
            );
        }
    }
    /**
     * @param array $form_params
     * @param integer $payment_id
     * @return model
     * @throws PaymentException
     */
    public function authorize(array $form_params = [], $payment_id)
    {
        $request_data = array_merge(
            ['form_params' => $form_params],
            $this->withHeaders()
        );
        try {
            $response = $this->client->post(
                "payments/$payment_id/authorize",
                $request_data
            );
            if ($response->getStatusCode() == 202) {
                $json = $this->getJson($response);
                $payment = new Payment((array) $json);
                PaymentEvent::dispatch($payment);
                return $payment;
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $json = $this->getJson($response);
            throw new PaymentException(
                sprintf(
                    'Authorize threw an exception - %s check %s - payment id %s',
                    $json->message,
                    $this->errorsToString($json),
                    $payment_id
                ),
                $response->getStatusCode(),
                null
            );
        }
    }
    /**
     * @param array $form_params
     * @param integer $payment_id
     * @return model Payment
     * @throws PaymentException
     */
    public function capture(array $form_params = [], $payment_id): Payment
    {
        $request_data = array_merge(
            ['form_params' => $form_params],
            $this->withHeaders()
        );
        try {
            $response = $this->client->post(
                "payments/$payment_id/capture",
                $request_data
            );
            if ($response->getStatusCode() == 202) {
                $json = $this->getJson($response);
                $payment = new Payment((array) $json);
                PaymentEvent::dispatch($payment);
                return $payment;
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $json = $this->getJson($response);

            throw new PaymentException(
                sprintf(
                    'Capture threw an exception - %s check %s - payment id %s',
                    $json->message,
                    $this->errorsToString($json),
                    $payment_id
                ),
                $response->getStatusCode(),
                null
            );
        }
    }
    /**
     *
     */
    public function refund(array $form_params = [], $payment_id)
    {
        $request_data = array_merge(
            ['form_params' => $form_params],
            $this->withHeaders()
        );
        try {
            $response = $this->client->post(
                "payments/$payment_id/refund",
                $request_data
            );
            if ($response->getStatusCode() == 202) {
                $json = $this->getJson($response);
                $payment = new Payment((array) $json);
                PaymentEvent::dispatch($payment);
                return $payment;
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $json = $this->getJson($response);

            throw new PaymentException(
                sprintf(
                    'Refund threw an exception - %s check %s - payment id %s',
                    $json->message,
                    $this->errorsToString($json),
                    $payment_id
                ),
                $response->getStatusCode(),
                null
            );
        }
    }
}
