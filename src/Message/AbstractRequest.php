<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Klarna\Rest\Transport\ConnectorInterface;
use MyOnlineStore\Omnipay\KlarnaCheckout\ItemBag;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->getParameter('locale');
    }

    /**
     * @return string
     */
    public function getLocaleRegionCode()
    {
        return explode('_', $this->getLocale())[1];
    }

    /**
     * @return int
     */
    public function getTaxAmount()
    {
        return $this->getParameter('tax_amount');
    }

    /**
     * @param ConnectorInterface $connector
     */
    public function setConnector(ConnectorInterface $connector)
    {
        $this->setParameter('connector', $connector);
    }

    /**
     * @inheritdoc
     */
    public function setItems($items)
    {
        if ($items && !$items instanceof ItemBag) {
            $items = new ItemBag($items);
        }

        return $this->setParameter('items', $items);
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->setParameter('locale', $locale);
    }

    /**
     * @param string[] $urls
     */
    public function setMerchantUrls(array $urls)
    {
        $this->setParameter('merchant_urls', $urls);
    }

    /**
     * @param int $value
     */
    public function setTaxAmount($value)
    {
        $this->setParameter('tax_amount', $value);
    }
}
