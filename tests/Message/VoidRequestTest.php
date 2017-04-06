<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\VoidRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\VoidResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class VoidRequestTest extends TestCase
{
    const TRANSACTION_REF = 'foo';

    /**
     * @var ClientInterface|\Mockery\MockInterface
     */
    private $httpClient;

    /**
     * @var VoidRequest
     */
    private $voidRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->httpClient = \Mockery::mock(ClientInterface::class);
        $this->voidRequest = new VoidRequest($this->httpClient, $this->getHttpRequest());
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
            [[], '/cancel'],
            [[['capture-id' => 1]], '/release-remaining-authorization']
        ];

    }

    /**
     * @dataProvider voidRequestCaptureDataProvider
     *
     * @param array  $captures
     * @param string $expectedPostRoute
     */
    public function testSendDataWillVoidOrderAndReturnResponse(array $captures, $expectedPostRoute)
    {
        $inputData = ['request-data' => 'yey?'];
        $orderData = ['captures' => $captures];
        $expectedData = [];

        $response = \Mockery::mock(Response::class);
        $response->shouldReceive('getBody')
            ->with(true)
            ->twice()
            ->andReturn(json_encode($orderData), json_encode($expectedData));
        $response->shouldReceive('json')->twice()->andReturn($orderData, $expectedData);

        $request = \Mockery::mock(RequestInterface::class);
        $request->shouldReceive('send')->twice()->andReturn($response);

        $this->httpClient->shouldReceive('createRequest')
            ->with(
                RequestInterface::GET,
                'localhost/ordermanagement/v1/orders/'.self::TRANSACTION_REF,
                null,
                null,
                ['auth' => ['merchant-32', 'very-secret-stuff']]
            )->andReturn($request);

        $this->httpClient->shouldReceive('createRequest')
            ->with(
                RequestInterface::POST,
                'localhost/ordermanagement/v1/orders/'.self::TRANSACTION_REF.$expectedPostRoute,
                ['Content-Type' => 'application/json'],
                json_encode($inputData),
                ['auth' => ['merchant-32', 'very-secret-stuff']]
            )->andReturn($request);

        $this->voidRequest->initialize([
            'base_url' => 'localhost',
            'merchant_id' => 'merchant-32',
            'secret' => 'very-secret-stuff',
            'transactionReference' => self::TRANSACTION_REF,
        ]);

        $response = $this->voidRequest->sendData($inputData);

        self::assertInstanceOf(VoidResponse::class, $response);
        self::assertSame($expectedData, $response->getData());
    }
}
