<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\Message\RequestInterface;

final class VoidRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     */
    public function getData()
    {
        $this->validate('transactionReference');

        return [];
    }

    /**
     * @inheritdoc
     */
    public function sendData($data)
    {
        $baseUrl = '/ordermanagement/v1/orders/'.$this->getTransactionReference();

        $order = $this->getResponseBody($this->sendRequest(RequestInterface::GET, $baseUrl, []));

        $voidUrl = $baseUrl.(empty($order['captures']) ? '/cancel' : '/release-remaining-authorization');

        return new VoidResponse(
            $this,
            $this->getResponseBody($this->sendRequest(RequestInterface::POST, $voidUrl, $data))
        );
    }
}
