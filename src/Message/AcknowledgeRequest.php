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
        $response = $this->sendRequest(
            RequestInterface::POST,
            sprintf(
                '/ordermanagement/v1/orders/%s/acknowledge',
                $this->getTransactionReference()
            ),
            $data
        );

        return new AcknowledgeResponse(
            $this,
            $this->getResponseBody($response),
            $response->getStatusCode()
        );
    }
}
