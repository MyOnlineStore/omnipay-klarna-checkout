<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

class ItemTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $taxRate = 2100;

        $item = new Item();
        $item->setTaxRate($taxRate);

        self::assertEquals($taxRate, $item->getTaxRate());
    }
}
