<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

class ItemTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $taxRate = 2100;
        $totalTaxAmount = 9000;

        $item = new Item();
        $item->setTaxRate($taxRate);
        $item->setTotalTaxAmount($totalTaxAmount);

        self::assertEquals($taxRate, $item->getTaxRate());
        self::assertEquals($totalTaxAmount, $item->getTotalTaxAmount());
    }
}
