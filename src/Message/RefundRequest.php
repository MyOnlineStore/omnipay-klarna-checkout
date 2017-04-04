<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

final class RefundRequest extends AbstractRequest
{
    use ItemDataTrait;

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        $data = ['refunded_amount' => $this->getAmountInteger()];

        if (null !== $items = $this->getItems()) {
            $data['order_lines'] = $this->getItemData($items);
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function sendData($data)
    {
        $url = '/ordermanagement/v1/orders/'.$this->getTransactionReference().'/refunds';

        return new RefundResponse($this, $this->getResponseBody($this->sendRequest("POST", $url, $data)));
    }
}
