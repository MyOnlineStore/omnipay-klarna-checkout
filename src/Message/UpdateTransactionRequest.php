<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\Message\RequestInterface;

final class UpdateTransactionRequest extends AbstractOrderRequest
{
    use ItemDataTrait;

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $this->validate('transactionReference');

        return $this->getOrderData();
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        return new UpdateTransactionResponse(
            $this,
            $this->getResponseBody(
                $this->sendRequest(
                    RequestInterface::POST,
                    sprintf('/checkout/v3/orders/%s', $this->getTransactionReference()),
                    $data
                )
            )
        );
    }
}
