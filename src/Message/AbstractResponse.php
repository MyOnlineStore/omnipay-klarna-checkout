<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{
    /**
     * @inheritdoc
     */
    public function getCode()
    {
        return $this->data['error_code'] ?? null;
    }

    /**
     * @inheritdoc
     */
    public function getMessage()
    {
        return $this->data['error_message'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getTransactionReference()
    {
        return $this->data['order_id'] ?? null;
    }

    public function isSuccessful(): bool
    {
        return !isset($this->data['error_code']);
    }
}
