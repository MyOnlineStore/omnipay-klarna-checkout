<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

final class FetchTransactionResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function getTransactionReference()
    {
        return $this->data['checkout']['order_id'];
    }

    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return parent::isSuccessful() &&
            (!empty($this->data['checkout']['status']) || !empty($this->data['management']['status']));
    }
}
