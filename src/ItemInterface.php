<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

interface ItemInterface extends \Omnipay\Common\ItemInterface
{
    /**
     * @return int
     */
    public function getTaxPrice();

    /**
     * @return int
     */
    public function getTaxRate();
}
