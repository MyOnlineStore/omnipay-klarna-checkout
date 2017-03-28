<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Klarna\Rest\Checkout\Order;
use MyOnlineStore\Omnipay\KlarnaCheckout\ItemInterface;

/**
 * Creates a Klarna Checkout order if it does not exist
 */
final class AuthorizeRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     */
    public function getData()
    {
        $this->validate(
            'amount',
            'currency',
            'items',
            'locale',
            'notifyUrl',
            'returnUrl',
            'tax_amount',
            'terms_url'
        );

        $orderLines = [];

        /** @var ItemInterface $item */
        foreach ($this->getItems() as $item) {
            $orderLines[] = [
                'name' => $item->getName(),
                'quantity' => $item->getQuantity(),
                'tax_rate' => $item->getTaxRate() * 100,
                'total_amount' => $item->getQuantity() * $item->getPrice() * 100,
                'total_tax_amount' => $item->getTotalTaxAmount() * 100,
                'unit_price' => $item->getPrice() * 100,
            ];
        }

        return [
            'locale' => str_replace('_', '-', $this->getLocale()),
            'order_amount' => $this->getAmountInteger(),
            'order_tax_amount' => $this->getTaxAmount() * 100,
            'order_lines' => $orderLines,
            'merchant_urls' => [
                'checkout' => $this->getReturnUrl(),
                'confirmation' => $this->getReturnUrl(),
                'push' => $this->getNotifyUrl(),
                'terms' => $this->getTermsUrl(),
            ],
            'purchase_country' =>  explode('_', $this->getLocale())[1],
            'purchase_currency' => $this->getCurrency(),
        ];
    }

    /**
     * @return string
     */
    public function getRenderUrl()
    {
        return $this->getParameter('render_url');
    }

    /**
     * @return string
     */
    public function getTermsUrl()
    {
        return $this->getParameter('terms_url');
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        $order = new Order($this->getConnector(), $this->getTransactionReference());

        if (!$this->getTransactionReference()) {
            $order->create($data);
        }

        $order->fetch();

        return new AuthorizeResponse($this, $order, $this->getRenderUrl());
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setRenderUrl($url)
    {
        $this->setParameter('render_url', $url);

        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setTermsUrl($url)
    {
        $this->setParameter('terms_url', $url);

        return $this;
    }
}
