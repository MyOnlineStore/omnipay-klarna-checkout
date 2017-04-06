<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\ClientInterface;
use Omnipay\Tests\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class RequestTestCase extends TestCase
{
    /**
     * @var ClientInterface|\Mockery\MockInterface
     */
    protected $httpClient;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldReceive('addListener')->with('request.error', \Mockery::type('callable'));

        $this->httpClient = \Mockery::mock(ClientInterface::class);
        $this->httpClient->shouldReceive('getEventDispatcher')
            ->once()
            ->andReturn($eventDispatcher);
    }
}
