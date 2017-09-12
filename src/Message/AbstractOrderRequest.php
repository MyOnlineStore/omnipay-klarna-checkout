<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

abstract class AbstractOrderRequest extends AbstractRequest
{
    use ItemDataTrait;

    /**
     * @return array
     */
    protected function getOrderData()
    {
        return [
            'locale' => str_replace('_', '-', $this->getLocale()),
            'order_amount' => $this->getAmountInteger(),
            'order_tax_amount' => $this->toCurrencyMinorUnits($this->getTaxAmount()),
            'order_lines' => $this->getItemData($this->getItems()),
            'purchase_country' =>  explode('_', $this->getLocale())[1],
            'purchase_currency' => $this->getCurrency(),
        ];
    }
}
