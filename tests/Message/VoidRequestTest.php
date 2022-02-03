<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\VoidRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\VoidResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class VoidRequestTest extends RequestTestCase
{
    public const TRANSACTION_REF = 'foo';

    /** @var VoidRequest */
    private $voidRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->voidRequest = new VoidRequest($this->httpClient, $this->getHttpRequest());
    }

    public function testGetDataWillReturnCorrectData()
    {
        $this->voidRequest->initialize(['transactionReference' => 'foo']);

        /** @noinspection PhpUnhandledExceptionInspection */
        self::assertEquals([], $this->voidRequest->getData());
    }

    public function testGetDataWillThrowExceptionForInvalidRequest()
    {
        $this->voidRequest->initialize([]);

        $this->expectException(InvalidRequestException::class);
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->voidRequest->getData();
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
        $expectedData = [];

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $this->httpClient->expects(self::exactly(2))
            ->method('request')
            ->withConsecutive(
                [
                    'GET',
                    self::BASE_URL . '/ordermanagement/v1/orders/' . self::TRANSACTION_REF,
                    $this->getExpectedHeaders(),
                    null,
                ],
                [
                    'POST',
                    self::BASE_URL . '/ordermanagement/v1/orders/' . self::TRANSACTION_REF . $expectedPostRoute,
                    \array_merge(['Content-Type' => 'application/json'], $this->getExpectedHeaders()),
                    \json_encode($inputData),
                ]
            )
            ->willReturn($response);

        $response->method('getBody')->willReturn($stream);
        $stream->expects(self::exactly(2))
            ->method('getContents')
            ->willReturnOnConsecutiveCalls(
                \json_encode(['captures' => $captures]),
                \json_encode($expectedData)
            );

        $response->expects(self::once())->method('getStatusCode')->willReturn(204);

        $this->voidRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'username' => self::USERNAME,
                'secret' => self::SECRET,
                'transactionReference' => self::TRANSACTION_REF,
            ]
        );

        $voidResponse = $this->voidRequest->sendData($inputData);

        self::assertInstanceOf(VoidResponse::class, $voidResponse);
        self::assertSame($expectedData, $voidResponse->getData());
    }

    /**
     * @return array
     */
    public function voidRequestCaptureDataProvider(): array
    {
        return [
            [[], '/cancel'],
            [[['capture-id' => 1]], '/release-remaining-authorization'],
        ];
    }
}
