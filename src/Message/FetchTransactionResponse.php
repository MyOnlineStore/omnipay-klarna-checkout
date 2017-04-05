<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

final class FetchTransactionResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return !empty($this->data['status']);
    }
}
