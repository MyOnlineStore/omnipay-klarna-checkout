<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

final class CaptureRequest extends AbstractRequest
{
    use ItemDataTrait;

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        $data = ['captured_amount' => $this->getAmountInteger()];

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
        $createResponse = $this->sendRequest("POST", $this->getEndpoint(), $data);
        $getResponse = $this->sendRequest(
            "GET",
            $this->getEndpoint().'/'.$createResponse->getHeader('capture-id'),
            []
        );

        return new CaptureResponse(
            $this,
            $this->getResponseBody($getResponse),
            $this->getTransactionReference()
        );
    }

    /**
     * @inheritdoc
     */
    private function getEndpoint()
    {
        return '/ordermanagement/v1/orders/'.$this->getTransactionReference().'/captures';
    }
}
