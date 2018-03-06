<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\Message\RequestInterface;

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
        $response = $this->sendRequest(RequestInterface::POST, $this->getEndpoint(), $data);

        return new CaptureResponse(
            $this,
            $this->getResponseBody($response),
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
