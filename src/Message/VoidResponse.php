<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Message\ResponseInterface;

final class VoidResponse extends AbstractResponse implements ResponseInterface
{
    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return true;
    }
}
