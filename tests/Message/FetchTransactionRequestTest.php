<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionResponse;
use MyOnlineStore\Tests\Omnipay\KlarnaCheckout\ExpectedAuthorizationHeaderTrait;
use Omnipay\Common\Exception\InvalidRequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class FetchTransactionRequestTest extends RequestTestCase
{
    use ExpectedAuthorizationHeaderTrait;

    /** @var FetchTransactionRequest */
    private $fetchTransactionRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fetchTransactionRequest = new FetchTransactionRequest($this->httpClient, $this->getHttpRequest());
    }

    public function testGetDataReturnsNull()
    {
        $this->fetchTransactionRequest->initialize(['transactionReference' => 'foo']);

        self::assertNull($this->fetchTransactionRequest->getData());
    }

    public function testGetDataThrowsExceptionWhenMissingTransactionReference()
    {
        $this->expectException(InvalidRequestException::class);

        $this->fetchTransactionRequest->initialize([]);
        $this->fetchTransactionRequest->getData();
    }

    public function testSendDataWillReturnResponseFromCheckoutApiForIncompleteOrder()
    {
        $expectedCheckoutData = ['status' => 'checkout_incomplete'];

        $response = $this->setExpectedGetRequest(
            $expectedCheckoutData,
            self::BASE_URL . '/checkout/v3/orders/foo'
        );
        $response->expects(self::once())->method('getStatusCode')->willReturn(200);

        $this->fetchTransactionRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'username' => self::USERNAME,
                'secret' => self::SECRET,
                'transactionReference' => 'foo',
            ]
        );

        $fetchResponse = $this->fetchTransactionRequest->sendData([]);

        self::assertInstanceOf(FetchTransactionResponse::class, $fetchResponse);
        self::assertSame(['checkout' => $expectedCheckoutData], $fetchResponse->getData());
    }

    public function testSendDataWillReturnResponseFromCheckoutApiForUnknownOrder()
    {
        $expectedCheckoutData = ['response-data' => 'nay!'];

        $response = $this->setExpectedGetRequest(
            $expectedCheckoutData,
            self::BASE_URL . '/checkout/v3/orders/foo'
        );
        $response->expects(self::once())->method('getStatusCode')->willReturn(200);

        $this->fetchTransactionRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'username' => self::USERNAME,
                'secret' => self::SECRET,
                'transactionReference' => 'foo',
            ]
        );

        $fetchResponse = $this->fetchTransactionRequest->sendData([]);

        self::assertInstanceOf(FetchTransactionResponse::class, $fetchResponse);
        self::assertSame(['checkout' => $expectedCheckoutData], $fetchResponse->getData());
    }

    public function testSendDataWillReturnResponseFromManagementApiForCompleteOrder()
    {
        $expectedCheckoutData = ['status' => 'checkout_complete'];
        $expectedManagementData = ['response-data' => 'yay!'];
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $this->httpClient->expects(self::exactly(2))
            ->method('request')
            ->withConsecutive(
                [
                    'GET',
                    self::BASE_URL . '/checkout/v3/orders/foo',
                    $this->getExpectedHeaders(),
                    null,
                ],
                [
                    'GET',
                    self::BASE_URL . '/ordermanagement/v1/orders/foo',
                    $this->getExpectedHeaders(),
                    null,
                ]
            )->willReturn($response);

        $response->method('getBody')->willReturn($stream);
        $stream->expects(self::exactly(2))
            ->method('getContents')
            ->willReturnOnConsecutiveCalls(
                \json_encode($expectedCheckoutData),
                \json_encode($expectedManagementData)
            );

        $this->fetchTransactionRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'username' => self::USERNAME,
                'secret' => self::SECRET,
                'transactionReference' => 'foo',
            ]
        );

        $fetchResponse = $this->fetchTransactionRequest->sendData([]);

        self::assertInstanceOf(FetchTransactionResponse::class, $fetchResponse);
        self::assertSame(
            ['checkout' => $expectedCheckoutData, 'management' => $expectedManagementData],
            $fetchResponse->getData()
        );
    }

    public function testSendDataWillReturnResponseFromManagementApiForDeletedCheckoutOrder()
    {
        $expectedCheckoutData = [];
        $expectedManagementData = ['response-data' => 'yay!'];
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $this->httpClient->expects(self::exactly(2))
            ->method('request')
            ->withConsecutive(
                [
                    'GET',
                    self::BASE_URL . '/checkout/v3/orders/foo',
                    $this->getExpectedHeaders(),
                    null,
                ],
                [
                    'GET',
                    self::BASE_URL . '/ordermanagement/v1/orders/foo',
                    $this->getExpectedHeaders(),
                    null,
                ]
            )->willReturn($response);

        $response->method('getBody')->willReturn($stream);
        $stream->expects(self::exactly(2))
            ->method('getContents')
            ->willReturnOnConsecutiveCalls(
                \json_encode($expectedCheckoutData),
                \json_encode($expectedManagementData)
            );

        $response->expects(self::once())->method('getStatusCode')->willReturn(404);

        $this->fetchTransactionRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'username' => self::USERNAME,
                'secret' => self::SECRET,
                'transactionReference' => 'foo',
            ]
        );

        $fetchResponse = $this->fetchTransactionRequest->sendData([]);

        self::assertInstanceOf(FetchTransactionResponse::class, $fetchResponse);
        self::assertSame(
            ['checkout' => $expectedCheckoutData, 'management' => $expectedManagementData],
            $fetchResponse->getData()
        );
    }
}
