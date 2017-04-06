<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\Message\RequestInterface;

final class AcknowledgeRequest extends AbstractRequest
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
        $url = '/ordermanagement/v1/orders/'.$this->getTransactionReference().'/acknowledge';

        return new AcknowledgeResponse(
            $this,
            $this->getResponseBody($this->sendRequest(RequestInterface::POST, $url, $data))
        );
    }
}
