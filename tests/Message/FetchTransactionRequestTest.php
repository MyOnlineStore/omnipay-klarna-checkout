<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\Response;
use GuzzleHttp\Message\RequestInterface;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class FetchTransactionRequestTest extends TestCase
{
    /**
     * @var ClientInterface|\Mockery\MockInterface
     */
    private $httpClient;

    /**
     * @var FetchTransactionRequest
     */
    private $fetchTransactionRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->httpClient = \Mockery::mock(ClientInterface::class);
        $this->fetchTransactionRequest = new FetchTransactionRequest($this->httpClient, $this->getHttpRequest());
    }

    public function testGetDataReturnsNull()
    {
        $this->fetchTransactionRequest->initialize(['transactionReference' => 'foo']);

        self::assertNull($this->fetchTransactionRequest->getData());
    }

    public function testGetDataThrowsExceptionWhenMissingTransactionReference()
    {
        $this->setExpectedException(InvalidRequestException::class);

        $this->fetchTransactionRequest->initialize([]);
        $this->fetchTransactionRequest->getData();
    }

    public function testSendData()
    {
        $inputData = ['request-data' => 'yey?'];
        $expectedData = ['response-data' => 'yey!'];

        $response = \Mockery::mock(Response::class);
        $response->shouldReceive('getBody')->with(true)->andReturn(json_encode($expectedData));
        $response->shouldReceive('json')->andReturn($expectedData);

        $request = \Mockery::mock(RequestInterface::class);
        $request->shouldReceive('send')->once()->andReturn($response);

        $this->httpClient->shouldReceive('createRequest')
            ->with(
                'GET',
                'localhost/ordermanagement/v1/orders/foo',
                null,
                null,
                ['auth' => ['merchant-32', 'very-secret-stuff']]
            )->andReturn($request);

        $this->fetchTransactionRequest->initialize([
            'base_url' => 'localhost',
            'merchant_id' => 'merchant-32',
            'secret' => 'very-secret-stuff',
            'transactionReference' => 'foo',
        ]);

        $response = $this->fetchTransactionRequest->sendData($inputData);

        self::assertInstanceOf(FetchTransactionResponse::class, $response);
        self::assertSame($expectedData, $response->getData());
    }
}
