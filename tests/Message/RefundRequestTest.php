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

    public function testGetDataWillReturnCorrectData()
    {
        $this->refundRequest->initialize(['transactionReference' => 'foo', 'amount' => '10.00']);
        $this->refundRequest->setItems([$this->getItemMock()]);

        self::assertEquals(
            [
                'refunded_amount' => 1000,
                'order_lines' => [$this->getExpectedOrderLine()],
            ],
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
