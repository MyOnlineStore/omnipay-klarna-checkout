<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\ClientInterface;
use Klarna\Rest\Transport\ConnectorInterface;
use MyOnlineStore\Omnipay\KlarnaCheckout\ItemBag;
use Symfony\Component\HttpFoundation\Request;

class AbstractRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClientInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $httpClient;

    /**
     * @var Request|\PHPUnit_Framework_MockObject_MockObject
     */
    private $httpRequest;

    /**
     * @var AbstractRequest
     */
    private $request;

    protected function setUp()
    {
        $this->httpClient = $this->getMock(ClientInterface::class);
        $this->httpRequest = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();

        /** @var AbstractRequest $request */
        $this->request = $this->getMockBuilder(AbstractRequest::class)
            ->setConstructorArgs([$this->httpClient, $this->httpRequest])
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
