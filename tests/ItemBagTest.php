<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout;

use MyOnlineStore\Omnipay\KlarnaCheckout\ItemBag;
use MyOnlineStore\Omnipay\KlarnaCheckout\ItemInterface;
use PHPUnit\Framework\TestCase;

final class ItemBagTest extends TestCase
{
    public function testAdd()
    {
        $item = $this->createMock(ItemInterface::class);

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
