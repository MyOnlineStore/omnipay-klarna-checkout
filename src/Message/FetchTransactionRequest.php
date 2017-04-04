<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

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
            $this->getResponseBody($this->sendRequest("GET", $url, $data))
        );
    }
}
