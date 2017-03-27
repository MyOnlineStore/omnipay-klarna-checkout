<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

final class Item extends \Omnipay\Common\Item implements ItemInterface
{
    /**
     * @inheritDoc
     */
    public function getTaxPrice()
    {
        return $this->getParameter('tax_price');
    }

    /**
     * @inheritDoc
     */
    public function getTaxRate()
    {
        return $this->getParameter('tax_rate');
    }

    /**
     * @param int $taxPrice
     */
    public function setTaxPrice($taxPrice)
    {
        $this->setParameter('tax_price', $taxPrice);
    }

    /**
     * @param int $taxRate
     */
    public function setTaxRate($taxRate)
    {
        $this->setParameter('tax_rate', $taxRate);
    }
}
