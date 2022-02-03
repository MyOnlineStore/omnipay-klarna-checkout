<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\CaptureRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\CaptureResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class CaptureRequestTest extends RequestTestCase
{
    public const CAPTURE_ID = 'bar';
    public const TRANSACTION_REF = 'foo';
    use ItemDataTestTrait;

    /** @var CaptureRequest */
    private $captureRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->captureRequest = new CaptureRequest($this->httpClient, $this->getHttpRequest());
    }

    /**
     * @return array
     */
    public function invalidRequestDataProvider(): array
    {
        return [
            [['transactionReference' => self::TRANSACTION_REF]],
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
        $this->captureRequest->initialize(
            [
                'transactionReference' => self::TRANSACTION_REF,
                'amount' => '100',
                'currency' => 'USD',
            ]
        );
        $this->captureRequest->setItems($items);

        /** @noinspection PhpUnhandledExceptionInspection */
        self::assertEquals(
            ['captured_amount' => 10000] + $expectedItemData,
            $this->captureRequest->getData()
        );
    }

    /**
     * @dataProvider invalidRequestDataProvider
     *
     * @param array $requestData
     */
    public function testGetDataWillThrowExceptionForInvalidRequest(array $requestData)
    {
        $this->captureRequest->initialize($requestData);

        $this->expectException(InvalidRequestException::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->captureRequest->getData();
    }

    public function testSendDataWillCreateCaptureAndReturnResponseWithCaptureData()
    {
        $requestdata = ['request-data' => 'yey?'];
        $responseData = ['response-data' => 'yey!'];

        $response = $this->setExpectedPostRequest(
            $requestdata,
            $responseData,
            self::BASE_URL . '/ordermanagement/v1/orders/' . self::TRANSACTION_REF . '/captures'
        );
        $response->expects(self::once())->method('getStatusCode')->willReturn(204);

        $this->captureRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'username' => self::USERNAME,
                'secret' => self::SECRET,
                'transactionReference' => self::TRANSACTION_REF,
            ]
        );

        $captureResponse = $this->captureRequest->sendData($requestdata);

        self::assertInstanceOf(CaptureResponse::class, $captureResponse);
        self::assertSame($responseData, $captureResponse->getData());
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
