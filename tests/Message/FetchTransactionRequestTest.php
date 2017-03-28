<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use Klarna\Rest\Checkout\Order;
use Klarna\Rest\Transport\Connector;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionResponse;
use Omnipay\Tests\TestCase;

class FetchTransactionRequestTest extends TestCase
{
    /**
     * @var FetchTransactionRequest
     */
    private $fetchTransactionRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->fetchTransactionRequest = new FetchTransactionRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetData()
    {
        self::assertNull($this->fetchTransactionRequest->getData());
    }

    public function testSendData()
    {
        $connector = \Mockery::spy(Connector::class);
        $this->fetchTransactionRequest->initialize(['connector' => $connector]);

        $request = \Mockery::mock(RequestInterface::class);
        $connector->shouldReceive('createRequest')
            ->with(null, 'GET', [])
            ->andReturn($request);

        $response = \Mockery::spy(ResponseInterface::class);
        $connector->shouldReceive('send')->with($request)->andReturn($response);

        $response->shouldReceive('getStatusCode')->andReturn('200');
        $response->shouldReceive('hasHeader')->with('Content-Type')->andReturn(true);
        $response->shouldReceive('getHeader')->with('Content-Type')->andReturn('application/json');
        $response->shouldReceive('json')->andReturn(['json' => 'foobar']);

        $response = $this->fetchTransactionRequest->sendData(['foo' => 'bar?']);

        self::assertInstanceOf(FetchTransactionResponse::class, $response);
        self::assertInstanceOf(Order::class, $response->getData());
    }
}
