<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;

final class ExtendAuthorizationRequest extends AbstractRequest
{
    /**
     * @return null
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionReference');

        return null;
    }

    /**
     * @param mixed $data
     *
     * @return ExtendAuthorizationResponse|ResponseInterface
     */
    public function sendData($data)
    {
        $responseBody = $this->getResponseBody(
            $this->sendRequest(
                RequestInterface::POST,
                sprintf('/ordermanagement/v1/orders/%s/extend-authorization-time', $this->getTransactionReference()),
                $data
            )
        );

        return new ExtendAuthorizationResponse(
            $this,
            \array_merge(
                $responseBody,
                ['order_id' => $this->getTransactionReference()]
            )
        );
    }
}
