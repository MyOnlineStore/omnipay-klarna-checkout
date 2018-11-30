<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\ItemDataTrait;

final class ItemDataTraitTest extends \PHPUnit\Framework\TestCase
{
    public function testGetItemDataWillReturnEmptyArrayForNullItemBag()
    {
        $instance = $this->getMockForTrait(ItemDataTrait::class);
        self::assertEquals([], $instance->getItemData(null));
    }
}
