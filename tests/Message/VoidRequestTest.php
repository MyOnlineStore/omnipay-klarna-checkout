<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use Klarna\Rest\Transport\Connector;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\VoidRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\VoidResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class VoidRequestTest extends TestCase
{
    /**
     * @var VoidRequest
     */
    private $voidRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->voidRequest = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetDataWillThrowExceptionForInvalidRequest()
    {
        $this->voidRequest->initialize([]);

        $this->setExpectedException(InvalidRequestException::class);
        $this->voidRequest->getData();
    }

    public function testGetDataWillReturnCorrectData()
    {
        $this->voidRequest->initialize(['transactionReference' => 'foo']);

        self::assertEquals([], $this->voidRequest->getData());
    }

    public function testSendDataWillVoidOrderAndReturnResponse()
    {
        $request = \Mockery::mock(RequestInterface::class);

        $response = \Mockery::spy(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->once()->andReturn('204');

        $connector = \Mockery::spy(Connector::class);
        $connector->shouldReceive('createRequest')
            ->with(\Mockery::type('string'), 'POST', [])
            ->once()
            ->andReturn($request);
        $connector->shouldReceive('send')->andReturn($response);

        $this->voidRequest->initialize(['connector' => $connector]);

        $response = $this->voidRequest->sendData([]);

        self::assertInstanceOf(VoidResponse::class, $response);
    }
}
