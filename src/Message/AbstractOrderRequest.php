<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Address;
use MyOnlineStore\Omnipay\KlarnaCheckout\WidgetOptions;

abstract class AbstractOrderRequest extends AbstractRequest
{
    use ItemDataTrait;

    /**
     * @return Address
     */
    public function getBillingAddress()
    {
        return $this->getParameter('billing_address');
    }

    /**
     * @return bool
     */
    public function getGuiAutofocus()
    {
        return $this->getParameter('gui_autofocus');
    }

    /**
     * @return bool
     */
    public function getGuiMinimalConfirmation()
    {
        return $this->getParameter('gui_minimal_confirmation');
    }

    /**
     * @return Address
     */
    public function getShippingAddress()
    {
        return $this->getParameter('shipping_address');
    }

    /**
     * @return WidgetOptions
     */
    public function getWidgetOptions()
    {
        return $this->getParameter('widget_options');
    }

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
     * @param bool $value
     *
     * @return $this
     */
    public function setGuiAutofocus($value)
    {
        $this->setParameter('gui_autofocus', $value);

        return $this;
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setGuiMinimalConfirmation($value)
    {
        $this->setParameter('gui_minimal_confirmation', $value);

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
     * @param array $widgetOptions
     *
     * @return $this
     */
    public function setWidgetOptions($widgetOptions)
    {
        $this->setParameter('widget_options', WidgetOptions::fromArray($widgetOptions));

        return $this;
    }

    /**
     * @return array
     */
    protected function getOrderData()
    {
        $data = [
            'locale' => str_replace('_', '-', $this->getLocale()),
            'order_amount' => $this->getAmountInteger(),
            'order_tax_amount' => $this->toCurrencyMinorUnits($this->getTaxAmount()),
            'order_lines' => $this->getItemData($this->getItems()),
            'purchase_country' => explode('_', $this->getLocale())[1],
            'purchase_currency' => $this->getCurrency(),
        ];

        if (null !== $shippingAddress = $this->getShippingAddress()) {
            $data['shipping_address'] = $shippingAddress->getArrayCopy();
        }

        if (null !== $billingAddress = $this->getBillingAddress()) {
            $data['billing_address'] = $billingAddress->getArrayCopy();
        }

        if (null !== $merchantReference1 = $this->getMerchantReference1()) {
            $data['merchant_reference1'] = $merchantReference1;
        }

        if (null !== $merchantReference2 = $this->getMerchantReference2()) {
            $data['merchant_reference2'] = $merchantReference2;
        }

        if (null !== $widgetOptions = $this->getWidgetOptions()) {
            $data['options'] = $widgetOptions->getArrayCopy();
        }

        $guiOptions = [];

        if (false === $this->getGuiAutofocus()) {
            $guiOptions[] = 'disable_autofocus';
        }

        if ($this->getGuiMinimalConfirmation()) {
            $guiOptions[] = 'minimal_confirmation';
        }

        if (!empty($guiOptions)) {
            $data['gui'] = ['options' => $guiOptions];
        }

        return $data;
    }
}
