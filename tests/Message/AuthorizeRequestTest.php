<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use Klarna\Rest\Transport\Connector;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AuthorizeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AuthorizeResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class AuthorizeRequestTest extends TestCase
{
    use ItemDataTestTrait;

    /**
     * @var AuthorizeRequest
     */
    private $authorizeRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->authorizeRequest = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetDataWillThrowExceptionForInvalidRequest()
    {
        $this->authorizeRequest->initialize([]);

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
        $request = \Mockery::mock(RequestInterface::class);

        $response = \Mockery::spy(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->twice()->andReturn('201', '200');
        $response->shouldReceive('hasHeader')->with(\Mockery::type('string'))->andReturn(true);
        $response->shouldReceive('getHeader')->with('Location')->andReturn('Over there!');
        $response->shouldReceive('getHeader')->with('Content-Type')->andReturn('application/json');
        $response->shouldReceive('json')->andReturn(['response-data' => 'yey!']);

        $connector = \Mockery::spy(Connector::class);
        $connector->shouldReceive('createRequest')
            ->with(\Mockery::type('string'), 'POST', ['json' => ['request-data' => 'yey?']])
            ->once()
            ->andReturn($request);
        $connector->shouldReceive('createRequest')
            ->with(\Mockery::type('string'), 'GET', [])
            ->once()
            ->andReturn($request);
        $connector->shouldReceive('send')->andReturn($response);

        $this->authorizeRequest->initialize(['connector' => $connector]);

        $response = $this->authorizeRequest->sendData(['request-data' => 'yey?']);

        self::assertInstanceOf(AuthorizeResponse::class, $response);
        self::assertEquals('yey!', $response->getData()['response-data']);
    }

    public function testSendDataWillReturnResponseIfTransactionIdAlreadySet()
    {
        $request = \Mockery::mock(RequestInterface::class);

        $response = \Mockery::spy(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->once()->andReturn('200');
        $response->shouldReceive('hasHeader')->with(\Mockery::type('string'))->andReturn(true);
        $response->shouldReceive('getHeader')->with('Location')->andReturn('Over there!');
        $response->shouldReceive('getHeader')->with('Content-Type')->andReturn('application/json');
        $response->shouldReceive('json')->andReturn(['response-data' => 'yey!']);

        $connector = \Mockery::spy(Connector::class);
        $connector->shouldReceive('createRequest')
            ->with(\Mockery::type('string'), 'GET', [])
            ->once()
            ->andReturn($request);
        $connector->shouldReceive('send')->andReturn($response);

        $this->authorizeRequest->initialize([
            'connector' => $connector,
            'transactionReference' => 'f60e69e8-464a-48c0-a452-6fd562540f37',
            'render_url' => 'localhost/render',
        ]);

        $response = $this->authorizeRequest->sendData(['request-data' => 'yey?']);

        self::assertInstanceOf(AuthorizeResponse::class, $response);
        self::assertEquals('yey!', $response->getData()['response-data']);
    }
}
