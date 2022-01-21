<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

final class FetchTransactionResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function getTransactionReference()
    {
        return $this->data['checkout']['order_id'] ?? $this->data['management']['order_id'];
    }

    public function isSuccessful(): bool
    {
        return parent::isSuccessful() &&
            (!empty($this->data['checkout']['status']) || !empty($this->data['management']['status']));
    }
}
