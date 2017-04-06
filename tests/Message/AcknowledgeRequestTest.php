<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use Klarna\Rest\Transport\Connector;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AcknowledgeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AcknowledgeResponse;
use Omnipay\Tests\TestCase;

class AcknowledgeRequestTest extends TestCase
{
    /**
     * @var AcknowledgeRequest
     */
    private $acknowledgeRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->acknowledgeRequest = new AcknowledgeRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetData()
    {
        $this->acknowledgeRequest->initialize(['transactionReference' => 'foo']);

        self::assertNull($this->acknowledgeRequest->getData());
    }

    public function testSendData()
    {
        $connector = \Mockery::spy(Connector::class);
        $this->acknowledgeRequest->initialize(['connector' => $connector]);

        $request = \Mockery::mock(RequestInterface::class);
        $connector->shouldReceive('createRequest')
            ->with(\Mockery::type('string'), 'POST', [])
            ->andReturn($request);

        $response = \Mockery::spy(ResponseInterface::class);
        $connector->shouldReceive('send')->with($request)->andReturn($response);

        $response->shouldReceive('getStatusCode')->andReturn('204');

        $response = $this->acknowledgeRequest->sendData(['foo' => 'bar?']);

        self::assertInstanceOf(AcknowledgeResponse::class, $response);
    }
}
