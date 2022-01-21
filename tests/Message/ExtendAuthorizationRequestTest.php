<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\ExtendAuthorizationRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class ExtendAuthorizationRequestTest extends RequestTestCase
{
    /** @var ExtendAuthorizationRequest */
    private $extendAuthorizationRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extendAuthorizationRequest = new ExtendAuthorizationRequest($this->httpClient, $this->getHttpRequest());
    }

    public function testGetDataThrowsExceptionWhenMissingTransactionReference()
    {
        $this->expectException(InvalidRequestException::class);

        $this->extendAuthorizationRequest->initialize([]);
        $this->extendAuthorizationRequest->getData();
    }

    public function testGetDataWithInvalidDataWillReturnNull()
    {
        $this->extendAuthorizationRequest->initialize(['transactionReference' => 'foo']);

        self::assertNull($this->extendAuthorizationRequest->getData());
    }

    public function testSendDataWillWillSendDataToKlarnaEndPointAndReturnCorrectResponse()
    {
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $this->httpClient->expects(self::once())
            ->method('request')
            ->with(
                'POST',
                \sprintf(
                    '%s/ordermanagement/v1/orders/%s/extend-authorization-time',
                    self::BASE_URL,
                    'foo'
                ),
                \array_merge(
                    ['Content-Type' => 'application/json'],
                    [
                        'Authorization' => \sprintf(
                            'Basic %s',
                            \base64_encode(
                                \sprintf(
                                    '%s:%s',
                                    null,
                                    self::SECRET
                                )
                            )
                        ),
                    ]
                ),
                \json_encode([])
            )
            ->willReturn($response);

        $response->method('getBody')->willReturn($stream);
        $stream->method('getContents')->willReturn(\json_encode(['hello' => 'world']));

        $this->extendAuthorizationRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'secret' => self::SECRET,
                'transactionReference' => 'foo',
            ]
        );

        $extendAuthorizationResponse = $this->extendAuthorizationRequest->sendData([]);

        self::assertSame('foo', $extendAuthorizationResponse->getTransactionReference());
        self::assertSame(
            [
                'hello' => 'world',
                'order_id' => 'foo',
            ],
            $extendAuthorizationResponse->getData()
        );
    }
}
