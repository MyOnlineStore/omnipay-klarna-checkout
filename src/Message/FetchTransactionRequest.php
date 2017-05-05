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
        $checkoutUrl = '/checkout/v3/orders/'.$this->getTransactionReference();
        $checkoutResponseBody = $this->getResponseBody($this->sendRequest(RequestInterface::GET, $checkoutUrl, $data));

        if (isset($checkoutResponseBody['status']) && 'checkout_complete' === $checkoutResponseBody['status']) {
            $managementUrl = '/ordermanagement/v1/orders/'.$this->getTransactionReference();

            return new FetchTransactionResponse(
                $this,
                $this->getResponseBody($this->sendRequest(RequestInterface::GET, $managementUrl, $data))
            );
        }

        return new FetchTransactionResponse($this, $checkoutResponseBody);
    }
}
