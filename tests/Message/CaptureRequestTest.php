<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use Klarna\Rest\Transport\Connector;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\CaptureRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\CaptureResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class CaptureRequestTest extends TestCase
{
    use ItemDataTestTrait;

    /**
     * @var CaptureRequest
     */
    private $captureRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->captureRequest = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
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
        $this->captureRequest->initialize($requestData);

        $this->setExpectedException(InvalidRequestException::class);
        $this->captureRequest->getData();
    }

    public function testGetDataWillReturnCorrectData()
    {
        $this->captureRequest->initialize(['transactionReference' => 'foo', 'amount' => '10.00']);
        $this->captureRequest->setItems([$this->getItemMock()]);

        self::assertEquals(
            [
                'captured_amount' => 1000,
                'order_lines' => [$this->getExpectedOrderLine()],
            ],
            $this->captureRequest->getData()
        );
    }

    public function testSendDataWillCreateCaptureAndReturnResponseWithCaptureData()
    {
        $request = \Mockery::mock(RequestInterface::class);

        $response = \Mockery::spy(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->twice()->andReturn('201', '200');
        $response->shouldReceive('hasHeader')->with(\Mockery::type('string'))->andReturn(true);
        $response->shouldReceive('getHeader')->with('Location')->andReturn('Over there!');
        $response->shouldReceive('getHeader')->with('Content-Type')->andReturn('application/json');
        $response->shouldReceive('json')->andReturn(['response-data' => 'yey!']);

        $connector = \Mockery::spy(Connector::class);
        $connector->shouldReceive('createRequest')
            ->with(\Mockery::type('string'), 'POST', ['json' => ['request-data' => 'yey?']])
            ->once()
            ->andReturn($request);
        $connector->shouldReceive('createRequest')
            ->with(\Mockery::type('string'), 'GET', [])
            ->once()
            ->andReturn($request);
        $connector->shouldReceive('send')->andReturn($response);

        $this->captureRequest->initialize(['connector' => $connector]);

        $response = $this->captureRequest->sendData(['request-data' => 'yey?']);

        self::assertInstanceOf(CaptureResponse::class, $response);
        self::assertEquals('yey!', $response->getData()['response-data']);
    }
}
