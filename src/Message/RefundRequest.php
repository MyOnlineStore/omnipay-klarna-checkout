<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Http\Exception\NetworkException;
use Omnipay\Common\Http\Exception\RequestException;

final class RefundRequest extends AbstractRequest
{
    use ItemDataTrait;

    /**
     * @inheritDoc
     *
     * @throws InvalidRequestException
     * @throws RequestException
     * @throws \InvalidArgumentException
     */
    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        $data = ['refunded_amount' => $this->getAmountInteger()];
        $items = $this->getItems();

        if (null !== $items) {
            $data['order_lines'] = $this->getItemData($items);
        }

        return $data;
    }

    /**
     * @inheritdoc
     *
     * @throws RequestException when the HTTP client is passed a request that is invalid and cannot be sent.
     * @throws NetworkException if there is an error with the network or the remote server cannot be reached.
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(
            'POST',
            \sprintf('/ordermanagement/v1/orders/%s/refunds', $this->getTransactionReference()),
            $data
        );

        return new RefundResponse(
            $this,
            $this->getResponseBody($response),
            $response->getStatusCode()
        );
    }
}
