<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Guzzle\Common\Exception\InvalidArgumentException;
use Guzzle\Http\Exception\RequestException;
use Guzzle\Http\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidRequestException;

final class RefundRequest extends AbstractRequest
{
    use ItemDataTrait;

    /**
     * @inheritDoc
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        $data = ['refunded_amount' => $this->getAmountInteger()];

        if (null !== $items = $this->getItems()) {
            $data['order_lines'] = $this->getItemData($items);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     *
     * @throws RequestException
     * @throws InvalidArgumentException
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(
            RequestInterface::POST,
            sprintf('/ordermanagement/v1/orders/%s/refunds', $this->getTransactionReference()),
            $data
        );

        return new RefundResponse(
            $this,
            $this->getResponseBody($response),
            $response->getStatusCode()
        );
    }
}
