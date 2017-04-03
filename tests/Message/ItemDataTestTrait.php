<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\ItemInterface;

trait ItemDataTestTrait
{
    /**
     * @return \Mockery\MockInterface|ItemInterface
     */
    protected function getItemMock()
    {
        $item = \Mockery::mock(ItemInterface::class);
        $item->shouldReceive('getName')->andReturn('item-name');
        $item->shouldReceive('getQuantity')->andReturn(1);
        $item->shouldReceive('getTaxRate')->andReturn(20);
        $item->shouldReceive('getQuantity')->andReturn(1);
        $item->shouldReceive('getPrice')->andReturn(10);
        $item->shouldReceive('getTotalTaxAmount')->andReturn(2);

        return $item;
    }

    /**
     * @return array
     */
    protected function getExpectedOrderLine()
    {
        return [
            'name' => 'item-name',
            'quantity' => 1,
            'tax_rate' => 2000,
            'total_amount' => 1000,
            'total_tax_amount' => 200,
            'unit_price' => 1000,
        ];
    }
}
