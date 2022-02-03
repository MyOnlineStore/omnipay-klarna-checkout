<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Http\Exception\NetworkException;
use Omnipay\Common\Http\Exception\RequestException;

final class FetchTransactionRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionReference');

        return null;
    }

    /**
     * @inheritDoc
     *
     * @throws RequestException when the HTTP client is passed a request that is invalid and cannot be sent.
     * @throws NetworkException if there is an error with the network or the remote server cannot be reached.
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(
            'GET',
            '/checkout/v3/orders/' . $this->getTransactionReference(),
            $data
        );

        $responseData['checkout'] = $this->getResponseBody($response);

        if (
            (isset($responseData['checkout']['status']) && 'checkout_complete' === $responseData['checkout']['status'])
            || 404 === $response->getStatusCode()
        ) {
            $responseData['management'] = $this->getResponseBody(
                $this->sendRequest(
                    'GET',
                    '/ordermanagement/v1/orders/' . $this->getTransactionReference(),
                    $data
                )
            );
        }

        return new FetchTransactionResponse($this, $responseData);
    }
}
