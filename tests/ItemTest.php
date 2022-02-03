<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout;

use MyOnlineStore\Omnipay\KlarnaCheckout\Item;
use PHPUnit\Framework\TestCase;

final class ItemTest extends TestCase
{
    public function testGetters()
    {
        $taxRate = 21;
        $totalTaxAmount = 9.45;
        $totalAmount = $totalTaxAmount / $taxRate * 100;
        $totalDiscountAmount = 1.00;
        $type = 'shipping_fee';
        $merchantData = 'foobar';

        $item = new Item();
        $item->setTaxRate($taxRate);
        $item->setTotalTaxAmount($totalTaxAmount);
        $item->setTotalAmount($totalAmount);
        $item->setTotalDiscountAmount($totalDiscountAmount);
        $item->setType($type);
        $item->setMerchantData($merchantData);

        self::assertEquals($taxRate, $item->getTaxRate());
        self::assertEquals($totalTaxAmount, $item->getTotalTaxAmount());
        self::assertEquals($totalAmount, $item->getTotalAmount());
        self::assertEquals($totalDiscountAmount, $item->getTotalDiscountAmount());
        self::assertEquals($type, $item->getType());
        self::assertEquals($merchantData, $item->getMerchantData());
    }
}
