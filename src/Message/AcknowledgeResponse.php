<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

final class AcknowledgeResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return true;
    }
}
