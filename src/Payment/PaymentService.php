<?php
/**
 *
 *
 */
namespace QuickPay\Payment;

use QuickPay\QuickPayService;
use QuickPay\Payment\Exception\PaymentException;

class PaymentService extends QuickPayService
{
    public function create(array $form_params)
    {
        $request_data = array_merge(
            ['form_params' => $form_params, 'debug' => true],
            $this->withHeaders()
        );

        try {
            $response = $this->client->post('payments', $request_data);
            if ($response->getStatusCode() == 201) {
                $body = $response->getBody();
                $json = json_decode($body);
                dd($json);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $body = $response->getBody();
            $json = json_decode($body);
            throw new PaymentException(
                $json->message,
                $response->getStatusCode(),
                $e
            );
        }
    }
}
