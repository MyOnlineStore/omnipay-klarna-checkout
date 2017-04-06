<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use Klarna\Rest\Transport\Connector;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\RefundRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\RefundResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
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
        $this->refundRequest = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
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
        $request = \Mockery::mock(RequestInterface::class);

        $response = \Mockery::spy(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->once()->andReturn('201');

        $connector = \Mockery::spy(Connector::class);
        $connector->shouldReceive('createRequest')
            ->with(\Mockery::type('string'), 'POST', ['json' => ['request-data' => 'yey?']])
            ->once()
            ->andReturn($request);
        $connector->shouldReceive('send')->andReturn($response);

        $this->refundRequest->initialize(['connector' => $connector]);

        self::assertInstanceOf(RefundResponse::class, $this->refundRequest->sendData(['request-data' => 'yey?']));
    }
}
