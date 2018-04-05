<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Guzzle\Common\Exception\InvalidArgumentException;
use Guzzle\Http\Exception\RequestException;
use Guzzle\Http\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidRequestException;

final class VoidRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionReference');

        return [];
    }

    /**
     * {@inheritdoc}
     *
     * @throws RequestException
     * @throws InvalidArgumentException
     */
    public function sendData($data)
    {
        $baseUrl = sprintf('/ordermanagement/v1/orders/%s', $this->getTransactionReference());
        $orderManagementResponse = $this->sendRequest(RequestInterface::GET, $baseUrl, []);

        $order = $this->getResponseBody($orderManagementResponse);

        $voidUrl = sprintf('%s/release-remaining-authorization', $baseUrl);

        if (empty($order['captures'])) {
            $voidUrl = sprintf('%s/cancel', $baseUrl);
        }

        $response = $this->sendRequest(RequestInterface::POST, $voidUrl, $data);

        return new VoidResponse(
            $this,
            $this->getResponseBody($response),
            $response->getStatusCode()
        );
    }
}
