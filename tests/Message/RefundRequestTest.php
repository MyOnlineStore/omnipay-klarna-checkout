<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\RefundRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\RefundResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class RefundRequestTest extends RequestTestCase
{
    use ItemDataTestTrait;

    /**
     * @var RefundRequest
     */
    private $refundRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->refundRequest = new RefundRequest($this->httpClient, $this->getHttpRequest());
    }

    /**
     * @return array
     */
    public function invalidRequestDataProvider()
    {
        return [
            [['transactionReference' => 'foo']],
            [['amount' => '10.00']],
        ];
    }

    /**
     * @dataProvider invalidRequestDataProvider
     *
     * @param array $requestData
     */
    public function testGetDataWillThrowExceptionForInvalidRequest(array $requestData)
    {
        $this->refundRequest->initialize($requestData);

        $this->setExpectedException(InvalidRequestException::class);
        $this->refundRequest->getData();
    }

    /**
     * @return array
     */
    public function validRequestDataProvider()
    {
        return [
            [null, []],   // No item data should return result without order_line entry
            [[$this->getItemMock()], ['order_lines' => [$this->getExpectedOrderLine()]]],
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
        $this->refundRequest->initialize(['transactionReference' => 'foo', 'amount' => '10.00']);
        $this->refundRequest->setItems($items);

        self::assertEquals(
            ['refunded_amount' => 1000] + $expectedItemData,
            $this->refundRequest->getData()
        );
    }

    public function testSendDataWillCreateRefundAndReturnResponse()
    {
        $inputData = ['request-data' => 'yey?'];
        $expectedData = [];

        $this->setExpectedPostRequest(
            $inputData,
            $expectedData,
            self::BASE_URL.'/ordermanagement/v1/orders/foo/refunds'
        );

        $this->refundRequest->initialize([
            'base_url' => self::BASE_URL,
            'username' => self::USERNAME,
            'secret' => self::SECRET,
            'transactionReference' => 'foo',
        ]);

        $refundResponse = $this->refundRequest->sendData($inputData);

        self::assertInstanceOf(RefundResponse::class, $refundResponse);
        self::assertSame($expectedData, $refundResponse->getData());
    }
}
