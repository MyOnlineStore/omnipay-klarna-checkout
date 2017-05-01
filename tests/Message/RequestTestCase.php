<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use Omnipay\Tests\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class RequestTestCase extends TestCase
{
    const BASE_URL = 'http://localhost';
    const MERCHANT_ID = 'merchant-32';
    const SECRET = 'very-secret-stuff';

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

    /**
     * @param array  $responseData
     * @param string $url
     */
    protected function setExpectedGetRequest(array $responseData, $url)
    {
        $response = \Mockery::mock(Response::class);
        $response->shouldReceive('json')->once()->andReturn($responseData);

        $request = \Mockery::mock(RequestInterface::class);
        $request->shouldReceive('send')->once()->andReturn($response);

        $this->httpClient->shouldReceive('createRequest')
            ->with(
                RequestInterface::GET,
                $url,
                null,
                null,
                ['auth' => [self::MERCHANT_ID, self::SECRET]]
            )->andReturn($request);
    }

    /**
     * @param array  $inputData
     * @param array  $responseData
     * @param string $url
     */
    protected function setExpectedPostRequest(array $inputData, array $responseData, $url)
    {
        $response = \Mockery::mock(Response::class);
        $response->shouldReceive('json')->once()->andReturn($responseData);

        $request = \Mockery::mock(RequestInterface::class);
        $request->shouldReceive('send')->once()->andReturn($response);

        $this->httpClient->shouldReceive('createRequest')
            ->with(
                RequestInterface::POST,
                $url,
                ['Content-Type' => 'application/json'],
                json_encode($inputData),
                ['auth' => [self::MERCHANT_ID, self::SECRET]]
            )->andReturn($request);
    }
}
