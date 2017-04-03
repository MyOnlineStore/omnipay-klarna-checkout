<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Klarna\Rest\OrderManagement\Order;

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
        $order = new Order($this->getConnector(), $this->getTransactionReference());
        $order->releaseRemainingAuthorization();

        return new VoidResponse($this, $order);
    }
}
