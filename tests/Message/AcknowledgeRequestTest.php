<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AcknowledgeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AcknowledgeResponse;

class AcknowledgeRequestTest extends RequestTestCase
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
        parent::setUp();
        $this->acknowledgeRequest = new AcknowledgeRequest($this->httpClient, $this->getHttpRequest());
    }

    public function testGetData()
    {
        $this->acknowledgeRequest->initialize(['transactionReference' => 'foo']);

        self::assertNull($this->acknowledgeRequest->getData());
    }

    public function testSendData()
    {
        $inputData = ['request-data' => 'yey?'];
        $expectedData = [];

        $response = \Mockery::mock(Response::class);
        $response->shouldReceive('getBody')->with(true)->once()->andReturn(json_encode($expectedData));
        $response->shouldReceive('json')->once()->andReturn($expectedData);

        $request = \Mockery::mock(RequestInterface::class);
        $request->shouldReceive('send')->once()->andReturn($response);

        $this->httpClient->shouldReceive('createRequest')
            ->with(
                RequestInterface::POST,
                'localhost/ordermanagement/v1/orders/foo/acknowledge',
                ['Content-Type' => 'application/json'],
                json_encode($inputData),
                ['auth' => ['merchant-32', 'very-secret-stuff']]
            )->andReturn($request);

        $this->acknowledgeRequest->initialize([
            'base_url' => 'localhost',
            'merchant_id' => 'merchant-32',
            'secret' => 'very-secret-stuff',
            'transactionReference' => 'foo',
        ]);

        $response = $this->acknowledgeRequest->sendData($inputData);

        self::assertInstanceOf(AcknowledgeResponse::class, $response);
        self::assertSame($expectedData, $response->getData());
    }
}
