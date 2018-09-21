<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\ItemInterface;

trait ItemDataTestTrait
{
    protected function getExpectedOrderLine(): array
    {
        return [
            'type' => 'shipping_fee',
            'name' => 'item-name',
            'quantity' => 1,
            'tax_rate' => 2003,
            'total_amount' => 1000,
            'total_tax_amount' => 20000,
            'unit_price' => 1000,
            'merchant_data' => 'foobar',
        ];
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getItemMock(): \PHPUnit_Framework_MockObject_MockObject
    {
        $item = $this->createMock(ItemInterface::class);
        $item->method('getType')->willReturn('shipping_fee');
        $item->method('getName')->willReturn('item-name');
        $item->method('getQuantity')->willReturn(1);
        $item->method('getTaxRate')->willReturn(20.03);
        $item->method('getQuantity')->willReturn(1);
        $item->method('getPrice')->willReturn(1000);
        $item->method('getTotalTaxAmount')->willReturn(200);
        $item->method('getMerchantData')->willReturn('foobar');

        return $item;
    }
}
