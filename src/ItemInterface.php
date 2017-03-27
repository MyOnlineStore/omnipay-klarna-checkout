<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

interface ItemInterface extends \Omnipay\Common\ItemInterface
{
    /**
     * Non-negative. In percent, two implicit decimals. I.e 2500 = 25%.
     *
     * @return int
     */
    public function getTaxRate();
}
