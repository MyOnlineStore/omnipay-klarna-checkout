<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\ItemInterface;
use PHPUnit\Framework\MockObject\MockObject;

trait ItemDataTestTrait
{
    protected function getExpectedOrderLine(): array
    {
        return [
            'type' => 'shipping_fee',
            'name' => 'item-name',
            'quantity' => 1,
            'tax_rate' => 2003,
            'total_amount' => 10000,
            'total_tax_amount' => 20000,
            'total_discount_amount' => 0,
            'unit_price' => 10000,
            'merchant_data' => 'foobar',
        ];
    }

    protected function getItemMock(): MockObject
    {
        $item = $this->createMock(ItemInterface::class);
        $item->method('getType')->willReturn('shipping_fee');
        $item->method('getName')->willReturn('item-name');
        $item->method('getQuantity')->willReturn(1);
        $item->method('getTaxRate')->willReturn(20.03);
        $item->method('getQuantity')->willReturn(1);
        $item->method('getPrice')->willReturn(100);
        $item->method('getTotalAmount')->willReturn(100);
        $item->method('getTotalTaxAmount')->willReturn(200);
        $item->method('getTotalDiscountAmount')->willReturn(0);
        $item->method('getMerchantData')->willReturn('foobar');

        return $item;
    }
}
