<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\UpdateMerchantReferencesRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\UpdateTransactionRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class UpdateMerchantReferencesRequestTest extends RequestTestCase
{
    public const TRANSACTION_REFERENCE = 1234;

    /** @var UpdateTransactionRequest */
    private $updateTransactionRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->updateTransactionRequest = new UpdateMerchantReferencesRequest(
            $this->httpClient,
            $this->getHttpRequest()
        );
    }

    public function testGetDataWillReturnCorrectData()
    {
        $this->updateTransactionRequest->initialize(
            [
                'merchant_reference1' => '12345',
                'merchant_reference2' => 678,
                'transactionReference' => self::TRANSACTION_REFERENCE,
            ]
        );

        self::assertEquals(
            ['merchant_reference1' => '12345', 'merchant_reference2' => 678],
            $this->updateTransactionRequest->getData()
        );
    }

    public function testSendDataWillUpdateManagementCustomerDetailsAndFailUpdatingMerchantReferences()
    {
        $inputData = ['merchant_reference1' => 'foo'];

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $this->httpClient->expects(self::once())
            ->method('request')
            ->withConsecutive(
                [
                    'PATCH',
                    \sprintf(
                        '%s/ordermanagement/v1/orders/%s/merchant-references',
                        self::BASE_URL,
                        self::TRANSACTION_REFERENCE
                    ),
                    \array_merge(
                        ['Content-Type' => 'application/json'],
                        $this->getExpectedHeaders()
                    ),
                    \json_encode($inputData),
                ]
            )
            ->willReturn($response);

        $response->expects(self::once())
            ->method('getBody')
            ->willReturn($stream);

        $stream->expects(self::once())
            ->method('getContents')
            ->willReturnOnConsecutiveCalls(
                \json_encode(['error_code' => 'doomsday'])
            );

        $this->updateTransactionRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'username' => self::USERNAME,
                'secret' => self::SECRET,
                'transactionReference' => self::TRANSACTION_REFERENCE,
            ]
        );

        self::assertFalse($this->updateTransactionRequest->sendData($inputData)->isSuccessful());
    }

    public function testSendDataWillUpdateOrderManagementMerchantReferences()
    {
        $merchantReferencesData = ['merchant_reference1' => 'baz', 'merchant_reference2' => 'quz'];

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $this->httpClient->expects(self::once())
            ->method('request')
            ->withConsecutive(
                [
                    'PATCH',
                    \sprintf(
                        '%s/ordermanagement/v1/orders/%s/merchant-references',
                        self::BASE_URL,
                        self::TRANSACTION_REFERENCE
                    ),
                    \array_merge(['Content-Type' => 'application/json'], $this->getExpectedHeaders()),
                    \json_encode($merchantReferencesData),
                ]
            )
            ->willReturn($response);

        $response->expects(self::once())
            ->method('getBody')
            ->willReturn($stream);

        $stream->expects(self::once())
            ->method('getContents')
            ->willReturnOnConsecutiveCalls(\json_encode([]));

        $this->updateTransactionRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'username' => self::USERNAME,
                'secret' => self::SECRET,
                'transactionReference' => self::TRANSACTION_REFERENCE,
            ]
        );

        $updateTransactionResponse = $this->updateTransactionRequest->sendData($merchantReferencesData);
        self::assertEmpty($updateTransactionResponse->getData());
        self::assertTrue($updateTransactionResponse->isSuccessful());
    }
}
