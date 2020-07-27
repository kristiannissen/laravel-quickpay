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
            $response = $e->getResponse();
            $json = $this->getJson($response);

            PaymentEvent::dispatch(new Payment());

            throw new PaymentException(
                sprintf(
                    'An Exception was thrown - %s check %s',
                    $json->message,
                    $this->errorsToString($json)
                ),
                $response->getStatusCode(),
                null
            );
        }
    }
    /**
     *
     */
    public function getAll()
    {
        try {
            $response = $this->client->get('payments', $this->withHeaders());
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
                    'An Exception was thrown - %s check %s',
                    $json->message,
                    $this->errorsToString($json)
                ),
                $response->getStatusCode(),
                null
            );
        }
    }
    /**
     *
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
                    'An Exception was thrown - %s check %s',
                    $json->message,
                    $this->errorsToString($json)
                ),
                $response->getStatusCode(),
                null
            );
        }
    }
    /**
     *
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
                return new Payment((array) $json);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $json = $this->getJson($response);
            throw new PaymentException(
                sprintf(
                    'An Exception was thrown - %s check %s',
                    $json->message,
                    $this->errorsToString($json)
                ),
                $response->getStatusCode(),
                null
            );
        }
    }
}
