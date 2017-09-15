<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Address;

abstract class AbstractOrderRequest extends AbstractRequest
{
    use ItemDataTrait;

    /**
     * @param array $billingAddress
     *
     * @return $this
     */
    public function setBillingAddress($billingAddress)
    {
        $this->setParameter('billing_address', Address::fromArray($billingAddress));

        return $this;
    }

    /**
     * @param array $shippingAddress
     *
     * @return $this
     */
    public function setShippingAddress($shippingAddress)
    {
        $this->setParameter('shipping_address', Address::fromArray($shippingAddress));

        return $this;
    }

    /**
     * @return Address
     */
    public function getBillingAddress()
    {
        return $this->getParameter('billing_address');
    }

    /**
     * @return Address
     */
    public function getShippingAddress()
    {
        return $this->getParameter('shipping_address');
    }

    /**
     * @return array
     */
    protected function getOrderData()
    {
        $data = [];

        if (null !== $shippingAddress = $this->getShippingAddress()) {
            $data['shipping_address'] = $shippingAddress->getArrayCopy();
        }

        if (null !== $billingAddress = $this->getBillingAddress()) {
            $data['billing_address'] = $billingAddress->getArrayCopy();
        }

        return array_merge(
            $data,
            [
                'locale' => str_replace('_', '-', $this->getLocale()),
                'order_amount' => $this->getAmountInteger(),
                'order_tax_amount' => $this->toCurrencyMinorUnits($this->getTaxAmount()),
                'order_lines' => $this->getItemData($this->getItems()),
                'purchase_country' => explode('_', $this->getLocale())[1],
                'purchase_currency' => $this->getCurrency(),
            ]
        );
    }
}
