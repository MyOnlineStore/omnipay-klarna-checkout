<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\Message\RequestInterface;

final class FetchTransactionRequest extends AbstractRequest
{
    /**
     * @inheritDoc
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
        $url = '/ordermanagement/v1/orders/'.$this->getTransactionReference();

        return new FetchTransactionResponse(
            $this,
            $this->getResponseBody($this->sendRequest(RequestInterface::GET, $url, $data))
        );
    }
}
