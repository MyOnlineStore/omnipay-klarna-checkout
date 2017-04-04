<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Message\RequestInterface;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AuthorizeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AuthorizeResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class AuthorizeRequestTest extends TestCase
{
    use ItemDataTestTrait;

    /**
     * @var ClientInterface|\Mockery\MockInterface
     */
    private $httpClient;

    /**
     * @var AuthorizeRequest
     */
    private $authorizeRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->httpClient = \Mockery::mock(ClientInterface::class);
        $this->authorizeRequest = new AuthorizeRequest($this->httpClient, $this->getHttpRequest());
    }

    /**
     * @return array
     */
    public function invalidRequestDataProvider()
    {
        $data = [
            'amount' => true,
            'currency' => true,
            'items' => [],
            'locale' => true,
            'notifyUrl' => true,
            'returnUrl' => true,
            'tax_amount' => true,
            'terms_url' => true,
        ];

        $cases = [];

        foreach ($data as $key => $value) {
            $cases[] = [array_diff_key($data, [$key => $value])];
        }

        return $cases;
    }

    /**
     * @dataProvider invalidRequestDataProvider
     *
     * @param array $requestData
     */
    public function testGetDataWillThrowExceptionForInvalidRequest(array $requestData)
    {
        $this->authorizeRequest->initialize($requestData);

        $this->setExpectedException(InvalidRequestException::class);
        $this->authorizeRequest->getData();
    }

    public function testGetDataWillReturnCorrectData()
    {
        $this->authorizeRequest->initialize([
            'locale' => 'nl_NL',
            'amount' => '100.00',
            'tax_amount' => 21,
            'returnUrl' => 'localhost/return',
            'notifyUrl' => 'localhost/notify',
            'termsUrl' => 'localhost/terms',
            'currency' => 'EUR',
        ]);
        $this->authorizeRequest->setItems([$this->getItemMock()]);

        self::assertEquals(
            [
                'locale' => 'nl-NL',
                'order_amount' => 10000,
                'order_tax_amount' => 2100,
                'order_lines' => [$this->getExpectedOrderLine()],
                'merchant_urls' => [
                    'checkout' => 'localhost/return',
                    'confirmation' => 'localhost/return',
                    'push' => 'localhost/notify',
                    'terms' => 'localhost/terms',
                ],
                'purchase_country' => 'NL',
                'purchase_currency' => 'EUR',
            ],
            $this->authorizeRequest->getData()
        );
    }

    public function testSendDataWillCreateOrderAndReturnResponse()
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
                'POST',
                'localhost/checkout/v3/orders',
                ['Content-Type' => 'application/json'],
                json_encode($inputData),
                ['auth' => ['merchant-32', 'very-secret-stuff']]
            )->andReturn($request);

        $this->authorizeRequest->initialize([
            'base_url' => 'localhost',
            'merchant_id' => 'merchant-32',
            'secret' => 'very-secret-stuff'
        ]);
        $this->authorizeRequest->setRenderUrl('localhost/render');

        $response = $this->authorizeRequest->sendData($inputData);

        self::assertInstanceOf(AuthorizeResponse::class, $response);
        self::assertSame($expectedData, $response->getData());
        self::assertEquals('localhost/render', $response->getRedirectUrl());
    }

    public function testSendDataWillFetchOrderAndReturnResponseIfTransactionIdAlreadySet()
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
                'localhost/checkout/v3/orders/f60e69e8-464a-48c0-a452-6fd562540f37',
                null,
                null,
                ['auth' => ['merchant-32', 'very-secret-stuff']]
            )->andReturn($request);

        $this->authorizeRequest->initialize([
            'base_url' => 'localhost',
            'merchant_id' => 'merchant-32',
            'secret' => 'very-secret-stuff',
            'transactionReference' => 'f60e69e8-464a-48c0-a452-6fd562540f37',
        ]);

        $response = $this->authorizeRequest->sendData($inputData);

        self::assertInstanceOf(AuthorizeResponse::class, $response);
        self::assertSame($expectedData, $response->getData());
    }
}
