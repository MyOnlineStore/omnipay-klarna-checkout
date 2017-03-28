<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

class ItemBagTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $item = $this->getMock(ItemInterface::class);

        $itemBag = new ItemBag();
        $itemBag->add($item);

        self::assertSame([$item], $itemBag->all());
    }

    public function testAddNonItem()
    {
        $itemArray = ['tax_rate' => 1000];

        $itemBag = new ItemBag();
        $itemBag->add($itemArray);

        self::assertEquals($itemArray, $itemBag->all()[0]->getParameters());
    }
}
