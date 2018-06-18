<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

final class FetchTransactionRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionReference');

        return null;
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(
            'GET',
            '/checkout/v3/orders/'.$this->getTransactionReference(),
            $data
        );

        $responseData['checkout'] = $this->getResponseBody($response);

        if ((isset($responseData['checkout']['status']) && 'checkout_complete' === $responseData['checkout']['status']) ||
            404 === $response->getStatusCode()
        ) {
            $responseData['management'] = $this->getResponseBody(
                $this->sendRequest(
                    'GET',
                    '/ordermanagement/v1/orders/'.$this->getTransactionReference(),
                    $data
                )
            );
        }

        return new FetchTransactionResponse($this, $responseData);
    }
}
