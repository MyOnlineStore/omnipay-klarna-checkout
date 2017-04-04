<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\ItemBag;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AbstractRequest;
use Omnipay\Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    /**
     * @var AbstractRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = $this->getMockBuilder(AbstractRequest::class)
            ->setConstructorArgs([$this->getHttpClient(), $this->getHttpRequest()])
            ->setMethods([])
            ->getMockForAbstractClass();
    }

    public function testGetters()
    {
        $locale = 'nl_NL';
        $taxAmount = 50.1;

        $this->request->setLocale($locale);
        $this->request->setTaxAmount($taxAmount);

        self::assertSame($locale, $this->request->getLocale());
        self::assertSame($taxAmount, $this->request->getTaxAmount());
    }

    public function testSetItemsWithItemBag()
    {
        $itemBag = $this->getMockBuilder(ItemBag::class)->disableOriginalConstructor()->getMock();

        $this->request->setItems($itemBag);

        self::assertSame($itemBag, $this->request->getParameters()['items']);
    }

    public function testSetItemsWithArray()
    {
        $itemsArray = [['tax_rate' => 10]];

        $this->request->setItems($itemsArray);

        self::assertEquals(new ItemBag($itemsArray), $this->request->getParameters()['items']);
    }
}
