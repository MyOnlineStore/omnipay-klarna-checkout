<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Message\RequestInterface;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\CaptureRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\CaptureResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class CaptureRequestTest extends TestCase
{
    const CAPTURE_ID = 'bar';
    const TRANSACTION_REF = 'foo';

    use ItemDataTestTrait;

    /**
     * @var ClientInterface|\Mockery\MockInterface
     */
    private $httpClient;

    /**
     * @var CaptureRequest
     */
    private $captureRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->httpClient = \Mockery::mock(ClientInterface::class);
        $this->captureRequest = new CaptureRequest($this->httpClient, $this->getHttpRequest());
    }

    /**
     * @return array
     */
    public function invalidRequestDataProvider()
    {
        return [
            [['transactionReference' => self::TRANSACTION_REF]],
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
        $this->captureRequest->initialize(['transactionReference' => self::TRANSACTION_REF, 'amount' => '10.00']);
        $this->captureRequest->setItems($items);

        self::assertEquals(
            ['captured_amount' => 1000] + $expectedItemData,
            $this->captureRequest->getData()
        );
    }

    public function testSendDataWillCreateCaptureAndReturnResponseWithCaptureData()
    {
        $inputData = ['request-data' => 'yey?'];
        $expectedData = ['response-data' => 'yey!'];

        $response = \Mockery::mock(Response::class);
        $response->shouldReceive('getHeader')->with('capture-id')->once()->andReturn(self::CAPTURE_ID);
        $response->shouldReceive('getBody')->with(true)->once()->andReturn(json_encode($expectedData));
        $response->shouldReceive('json')->once()->andReturn($expectedData);

        $request = \Mockery::mock(RequestInterface::class);
        $request->shouldReceive('send')->twice()->andReturn($response);

        $this->httpClient->shouldReceive('createRequest')
            ->with(
                'POST',
                'localhost/ordermanagement/v1/orders/'.self::TRANSACTION_REF.'/captures',
                ['Content-Type' => 'application/json'],
                json_encode($inputData),
                ['auth' => ['merchant-32', 'very-secret-stuff']]
            )->andReturn($request);

        $this->httpClient->shouldReceive('createRequest')
            ->with(
                'GET',
                'localhost/ordermanagement/v1/orders/'.self::TRANSACTION_REF.'/captures/'.self::CAPTURE_ID,
                null,
                null,
                ['auth' => ['merchant-32', 'very-secret-stuff']]
            )->andReturn($request);

        $this->captureRequest->initialize([
            'base_url' => 'localhost',
            'merchant_id' => 'merchant-32',
            'secret' => 'very-secret-stuff',
            'transactionReference' => self::TRANSACTION_REF,
        ]);

        $response = $this->captureRequest->sendData($inputData);

        self::assertInstanceOf(CaptureResponse::class, $response);
        self::assertSame($expectedData, $response->getData());
    }
}
