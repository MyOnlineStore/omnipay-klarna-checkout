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
    const USERNAME = 'merchant-32';
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
     *
     * @return \Mockery\MockInterface
     */
    protected function setExpectedGetRequest(array $responseData, $url)
    {
        return $this->setExpectedRequest(RequestInterface::GET, $url, [], [], $responseData);
    }

    /**
     * @param array  $inputData
     * @param array  $responseData
     * @param string $url
     *
     * @return \Mockery\MockInterface
     */
    protected function setExpectedPatchRequest(array $inputData, array $responseData, $url)
    {
        return $this->setExpectedRequest(
            RequestInterface::PATCH,
            $url,
            ['Content-Type' => 'application/json'],
            $inputData,
            $responseData
        );
    }

    /**
     * @param array  $inputData
     * @param array  $responseData
     * @param string $url
     *
     * @return \Mockery\MockInterface
     */
    protected function setExpectedPostRequest(array $inputData, array $responseData, $url)
    {
        return $this->setExpectedRequest(
            RequestInterface::POST,
            $url,
            ['Content-Type' => 'application/json'],
            $inputData,
            $responseData
        );
    }

    /**
     * @param string $requestMethod
     * @param string $url
     * @param array  $headers
     * @param array  $inputData
     * @param array  $responseData
     *
     * @return \Mockery\MockInterface
     */
    private function setExpectedRequest($requestMethod, $url, array $headers, array $inputData, array $responseData)
    {
        $response = \Mockery::mock(Response::class);
        $response->shouldReceive('getBody')->with(true)->once()->andReturn(json_encode($responseData));
        $response->shouldReceive('json')->once()->andReturn($responseData);

        $request = \Mockery::mock(RequestInterface::class);
        $request->shouldReceive('send')->once()->andReturn($response);

        $this->httpClient->shouldReceive('createRequest')
            ->with(
                $requestMethod,
                $url,
                $headers,
                json_encode($inputData),
                ['auth' => [self::USERNAME, self::SECRET]]
            )->andReturn($request);

        return $response;
    }
}
