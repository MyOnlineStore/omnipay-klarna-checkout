<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout;

use MyOnlineStore\Omnipay\KlarnaCheckout\Item;

class ItemTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $taxRate = 21;
        $totalTaxAmount = 9.45;
        $type = 'shipping_fee';
        $merchantData = 'foobar';

        $item = new Item();
        $item->setTaxRate($taxRate);
        $item->setTotalTaxAmount($totalTaxAmount);
        $item->setType($type);
        $item->setMerchantData($merchantData);

        self::assertEquals($taxRate, $item->getTaxRate());
        self::assertEquals($totalTaxAmount, $item->getTotalTaxAmount());
        self::assertEquals($type, $item->getType());
        self::assertEquals($merchantData, $item->getMerchantData());
    }
}
