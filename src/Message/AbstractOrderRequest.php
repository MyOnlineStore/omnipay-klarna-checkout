<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Address;
use MyOnlineStore\Omnipay\KlarnaCheckout\Customer;
use MyOnlineStore\Omnipay\KlarnaCheckout\ItemBag;
use MyOnlineStore\Omnipay\KlarnaCheckout\WidgetOptions;

abstract class AbstractOrderRequest extends AbstractRequest
{
    use ItemDataTrait;

    /**
     * @return Address|null
     */
    public function getBillingAddress()
    {
        return $this->getParameter('billing_address');
    }

    /**
     * @return Customer|null
     */
    public function getCustomer()
    {
        return $this->getParameter('customer');
    }

    /**
     * @return bool|null
     */
    public function getGuiAutofocus()
    {
        return $this->getParameter('gui_autofocus');
    }

    /**
     * @return bool|null
     */
    public function getGuiMinimalConfirmation()
    {
        return $this->getParameter('gui_minimal_confirmation');
    }

    /**
     * @return string|null
     */
    public function getPurchaseCountry()
    {
        return $this->getParameter('purchase_country');
    }

    /**
     * @return Address|null
     */
    public function getShippingAddress()
    {
        return $this->getParameter('shipping_address');
    }

    /**
     * @return string[]|null ISO 3166 alpha-2 codes of shipping countries, or null if none are specified
     */
    public function getShippingCountries()
    {
        return $this->getParameter('shipping_countries');
    }

    /**
     * @return WidgetOptions|null
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
    public function setBillingAddress($billingAddress): self
    {
        $this->setParameter('billing_address', Address::fromArray($billingAddress));

        return $this;
    }

    /**
     * @param array $customer
     *
     * @return $this
     */
    public function setCustomer(array $customer): self
    {
        $this->setParameter('customer', Customer::fromArray($customer));

        return $this;
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setGuiAutofocus(bool $value): self
    {
        $this->setParameter('gui_autofocus', $value);

        return $this;
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setGuiMinimalConfirmation(bool $value): self
    {
        $this->setParameter('gui_minimal_confirmation', $value);

        return $this;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setPurchaseCountry(string $value): self
    {
        $this->setParameter('purchase_country', $value);

        return $this;
    }

    /**
     * @param array $shippingAddress
     *
     * @return $this
     */
    public function setShippingAddress(array $shippingAddress): self
    {
        $this->setParameter('shipping_address', Address::fromArray($shippingAddress));

        return $this;
    }

    /**
     * @param string[] $countries ISO 3166 alpha-2 codes of shipping countries
     *
     * @return $this
     */
    public function setShippingCountries(array $countries): self
    {
        $this->setParameter('shipping_countries', $countries);

        return $this;
    }

    /**
     * @param array $widgetOptions
     *
     * @return $this
     */
    public function setWidgetOptions(array $widgetOptions): self
    {
        $this->setParameter('widget_options', WidgetOptions::fromArray($widgetOptions));

        return $this;
    }

    /**
     * @return array
     */
    protected function getOrderData(): array
    {
        $data = [
            'order_amount' => $this->getAmountInteger(),
            'order_tax_amount' => null === $this->getTaxAmount() ? 0 : (int) $this->getTaxAmount()->getAmount(),
            'order_lines' => $this->getItemData($this->getItems() ?? new ItemBag()),
            'purchase_currency' => $this->getCurrency(),
            'purchase_country' => $this->getPurchaseCountry(),
        ];

        if (null !== $locale = $this->getLocale()) {
            $data['locale'] = \str_replace('_', '-', $locale);
        }

        if (null !== $shippingCountries = $this->getShippingCountries()) {
            $data['shipping_countries'] = $shippingCountries;
        }

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

        if (null !== $customer = $this->getCustomer()) {
            $data['customer'] = $customer->getArrayCopy();
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
