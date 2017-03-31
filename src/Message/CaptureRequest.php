<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Klarna\Rest\OrderManagement\Order;

final class CaptureRequest extends AbstractRequest
{
    use ItemDataTrait;

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        return [
            'captured_amount' => $this->getAmountInteger(),
            'order_lines' => $this->getItemData($this->getItems()),
        ];
    }

    /**
     * @inheritdoc
     */
    public function sendData($data)
    {
        $order = new Order($this->getConnector(), $this->getTransactionReference());

        $capture = $order->createCapture($data);

        return new CaptureResponse($this, $capture->fetch(), $this->getTransactionReference());
    }
}
