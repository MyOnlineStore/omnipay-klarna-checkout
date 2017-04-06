<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
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

        $response = \Mockery::mock(Response::class);
        $response->shouldReceive('getBody')->with(true)->once()->andReturn(json_encode($expectedData));
        $response->shouldReceive('json')->once()->andReturn($expectedData);

        $request = \Mockery::mock(RequestInterface::class);
        $request->shouldReceive('send')->once()->andReturn($response);

        $this->httpClient->shouldReceive('createRequest')
            ->with(
                RequestInterface::POST,
                'localhost/ordermanagement/v1/orders/foo/refunds',
                ['Content-Type' => 'application/json'],
                json_encode($inputData),
                ['auth' => ['merchant-32', 'very-secret-stuff']]
            )->andReturn($request);

        $this->refundRequest->initialize([
            'base_url' => 'localhost',
            'merchant_id' => 'merchant-32',
            'secret' => 'very-secret-stuff',
            'transactionReference' => 'foo',
        ]);

        $response = $this->refundRequest->sendData($inputData);

        self::assertInstanceOf(RefundResponse::class, $response);
        self::assertSame($expectedData, $response->getData());
    }
}
