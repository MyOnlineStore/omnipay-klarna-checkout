<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use Guzzle\Common\Event;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\Response;
use MyOnlineStore\Omnipay\KlarnaCheckout\ItemBag;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AbstractRequest;
use Omnipay\Tests\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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

    public function testClientErrorPropagationIsStopped()
    {
        $eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldReceive('addListener')
            ->with('request.error', \Mockery::on(
                function (callable $functionToValidate) {
                    $eventA = \Mockery::mock(
                        Event::class.'[stopPropagation]',
                        [['response' => new Response(403)]]
                    );
                    $eventA->shouldReceive('stopPropagation')->once();

                    $eventB = \Mockery::mock(
                        Event::class.'[stopPropagation]',
                        [['response' => new Response(200)]]
                    );
                    $eventB->shouldNotReceive('stopPropagation');

                    $functionToValidate($eventA);
                    $functionToValidate($eventB);

                    return true;
                }
            ))->once();

        $httpClient = \Mockery::mock(ClientInterface::class);
        $httpClient->shouldReceive('getEventDispatcher')->once()->andReturn($eventDispatcher);

        $this->request = $this->getMockForAbstractClass(
            AbstractRequest::class,
            [$httpClient, $this->getHttpRequest()]
        );
    }
}
