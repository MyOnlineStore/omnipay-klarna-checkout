<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Klarna\Rest\OrderManagement\Order;

final class RefundRequest extends AbstractRequest
{
    use ItemDataTrait;

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        return [
            'refunded_amount' => $this->getAmountInteger(),
            'order_lines' => $this->getItemData($this->getItems()),
        ];
    }

    /**
     * @inheritdoc
     */
    public function sendData($data)
    {
        $order = new Order($this->getConnector(), $this->getTransactionReference());
        $order->refund($data);

        return new RefundResponse($this, $order);
    }
}
