<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use Klarna\Rest\Transport\ConnectorInterface;
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
        /** @var ConnectorInterface $connector */
        $connector = $this->getMock(ConnectorInterface::class);
        $locale = 'nl_NL';
        $taxAmount = 5000;

        $this->request->setConnector($connector);
        $this->request->setLocale($locale);
        $this->request->setTaxAmount($taxAmount);

        self::assertSame($connector, $this->request->getConnector());
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
        $itemsArray = [['tax_rate' => 1000]];

        $this->request->setItems($itemsArray);

        self::assertEquals(new ItemBag($itemsArray), $this->request->getParameters()['items']);
    }
}
