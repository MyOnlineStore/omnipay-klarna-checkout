<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function getTransactionReference()
    {
        return $this->data['order_id'];
    }
}

