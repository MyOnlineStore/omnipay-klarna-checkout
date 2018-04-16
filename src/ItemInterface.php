<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

interface ItemInterface extends \Omnipay\Common\ItemInterface
{
    /**
     * @return string
     */
    public function getMerchantData();

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

    /**
     * Product type
     *
     * @return string
     */
    public function getType();
}
