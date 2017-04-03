<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use Klarna\Rest\OrderManagement\Capture;
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

    /**
     * @return array
     */
    public function voidRequestCaptureDataProvider()
    {
        return [
            [[], '/cancel$/'],
            [[[Capture::ID_FIELD => 1]], '/release-remaining-authorization$/']
        ];

    }

    /**
     * @dataProvider voidRequestCaptureDataProvider
     *
     * @param array  $captures
     * @param string$expectedPostRoute
     */
    public function testSendDataWillVoidOrderAndReturnResponse(array $captures, $expectedPostRoute)
    {
        $request = \Mockery::mock(RequestInterface::class);

        $response = \Mockery::spy(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->twice()->andReturn('200', '204');
        $response->shouldReceive('hasHeader')->with(\Mockery::type('string'))->andReturn(true);
        $response->shouldReceive('getHeader')->with('Location')->andReturn('Over there!');
        $response->shouldReceive('getHeader')->with('Content-Type')->andReturn('application/json');
        $response->shouldReceive('json')->andReturn(['captures' => $captures]);

        $connector = \Mockery::spy(Connector::class);
        $connector->shouldReceive('createRequest')
            ->with(\Mockery::type('string'), 'GET', [])
            ->once()
            ->andReturn($request);
        $connector->shouldReceive('createRequest')
            ->with($expectedPostRoute, 'POST', [])
            ->once()
            ->andReturn($request);
        $connector->shouldReceive('send')->andReturn($response);

        $this->voidRequest->initialize(['connector' => $connector]);

        $response = $this->voidRequest->sendData([]);

        self::assertInstanceOf(VoidResponse::class, $response);
    }
}
