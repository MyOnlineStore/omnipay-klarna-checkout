<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

interface ItemInterface extends \Omnipay\Common\ItemInterface
{
    /**
     * Non-negative percentage (i.e. 25 = 25%)
     *
     * @return float
     */
    public function getTaxRate();

    /**
     * Total amount of tax
     *
     * @return float
     */
    public function getTotalTaxAmount();
}
