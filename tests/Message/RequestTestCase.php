<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Tests\Omnipay\KlarnaCheckout\ExpectedAuthorizationHeaderTrait;
use Omnipay\Common\Http\ClientInterface;
use Omnipay\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

abstract class RequestTestCase extends TestCase
{
    use ExpectedAuthorizationHeaderTrait;

    const BASE_URL = 'http://localhost';
    const SECRET = 'very-secret-stuff';
    const USERNAME = 'merchant-32';

    /**
     * @var ClientInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpClient;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->httpClient = $this->createMock(ClientInterface::class);
    }

    /**
     * @param array  $responseData
     * @param string $url
     *
     * @return ResponseInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function setExpectedGetRequest(array $responseData, $url)
    {
        return $this->setExpectedRequest('GET', $url, [], null, $responseData);
    }

    /**
     * @param array  $inputData
     * @param array  $responseData
     * @param string $url
     *
     * @return ResponseInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function setExpectedPatchRequest(array $inputData, array $responseData, $url)
    {
        return $this->setExpectedRequest(
            'PATCH',
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
     * @return ResponseInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function setExpectedPostRequest(array $inputData, array $responseData, $url)
    {
        return $this->setExpectedRequest(
            'POST',
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
     * @return ResponseInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function setExpectedRequest(
        $requestMethod,
        $url,
        array $headers,
        array $inputData = null,
        array $responseData
    ) {
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $this->httpClient->expects(self::once())
            ->method('request')
            ->with(
                $requestMethod,
                $url,
                array_merge(
                    $headers,
                    $this->getExpectedHeaders()
                ),
                $inputData === null ? null : \json_encode($inputData),
                []
            )
            ->willReturn($response);

        $response->method('getBody')->willReturn($stream);
        $stream->method('getContents')->willReturn(\json_encode($responseData));

        return $response;
    }
}
