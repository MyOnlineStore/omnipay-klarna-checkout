<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\RefundRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\RefundResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class RefundRequestTest extends RequestTestCase
{
    use ItemDataTestTrait;

    /** @var RefundRequest */
    private $refundRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refundRequest = new RefundRequest($this->httpClient, $this->getHttpRequest());
    }

    /**
     * @return array
     */
    public function invalidRequestDataProvider(): array
    {
        return [
            [['transactionReference' => 'foo']],
            [['amount' => '10.00']],
        ];
    }

    /**
     * @dataProvider validRequestDataProvider
     *
     * @param array|null $items
     * @param array      $expectedItemData
     */
    public function testGetDataWillReturnCorrectData($items, array $expectedItemData)
    {
        $this->refundRequest->initialize(['transactionReference' => 'foo', 'amount' => '10.00', 'currency' => 'USD']);
        $this->refundRequest->setItems($items);

        /** @noinspection PhpUnhandledExceptionInspection */
        self::assertEquals(
            ['refunded_amount' => 1000] + $expectedItemData,
            $this->refundRequest->getData()
        );
    }

    /**
     * @dataProvider invalidRequestDataProvider
     *
     * @param array $requestData
     */
    public function testGetDataWillThrowExceptionForInvalidRequest(array $requestData)
    {
        $this->refundRequest->initialize($requestData);

        $this->expectException(InvalidRequestException::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->refundRequest->getData();
    }

    public function testSendDataWillCreateRefundAndReturnResponse()
    {
        $inputData = ['request-data' => 'yey?'];
        $expectedData = [];

        $response = $this->setExpectedPostRequest(
            $inputData,
            $expectedData,
            self::BASE_URL . '/ordermanagement/v1/orders/foo/refunds'
        );

        $response->expects(self::once())->method('getStatusCode')->willReturn(204);

        $this->refundRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'username' => self::USERNAME,
                'secret' => self::SECRET,
                'transactionReference' => 'foo',
            ]
        );

        $refundResponse = $this->refundRequest->sendData($inputData);

        self::assertInstanceOf(RefundResponse::class, $refundResponse);
        self::assertSame($expectedData, $refundResponse->getData());
    }

    /**
     * @return array
     */
    public function validRequestDataProvider(): array
    {
        return [
            [null, []],   // No item data should return result without order_line entry
            [[$this->getItemMock()], ['order_lines' => [$this->getExpectedOrderLine()]]],
        ];
    }
}
