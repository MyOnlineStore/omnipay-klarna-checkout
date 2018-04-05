<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Guzzle\Common\Exception\InvalidArgumentException;
use Guzzle\Http\Exception\RequestException;
use Guzzle\Http\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidRequestException;

final class CaptureRequest extends AbstractRequest
{
    use ItemDataTrait;

    /**
     * {@inheritDoc}
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        $data = ['captured_amount' => $this->getAmountInteger()];

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
            sprintf(
                '/ordermanagement/v1/orders/%s/captures',
                $this->getTransactionReference()
            ),
            $data
        );

        return new CaptureResponse(
            $this,
            $this->getResponseBody($response),
            $this->getTransactionReference(),
            $response->getStatusCode()
        );
    }
}
