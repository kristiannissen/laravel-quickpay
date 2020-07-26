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
                $body = $response->getBody();
                $json = json_decode($body);

                $payment = new Payment((array) $json);
                PaymentEvent::dispatch($payment);

                return $payment;
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $body = $response->getBody();
            $json = json_decode($body);
            // TODO: Improve error messages

            PaymentEvent::dispatch(new Payment());

            throw new PaymentException(
                $json->message,
                $response->getStatusCode(),
                null
            );
        }
    }
}
