<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Klarna\Rest\Transport\ConnectorInterface;
use MyOnlineStore\Omnipay\KlarnaCheckout\ItemBag;

/**
 * @method ItemBag|null getItems()
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * @return ConnectorInterface
     */
    public function getConnector()
    {
        return $this->getParameter('connector');
    }

    /**
     * RFC 1766 customer's locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->getParameter('locale');
    }

    /**
     * Non-negative, minor units. The total tax amount of the order.
     *
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
     * @param int $value
     */
    public function setTaxAmount($value)
    {
        $this->setParameter('tax_amount', $value);
    }
}
