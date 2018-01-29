<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\CaptureRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\CaptureResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class CaptureRequestTest extends RequestTestCase
{
    const CAPTURE_ID = 'bar';
    const TRANSACTION_REF = 'foo';

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
        parent::setUp();
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

        $request = \Mockery::mock(RequestInterface::class);
        $request->shouldReceive('send')->once()->andReturn($response);

        $this->httpClient->shouldReceive('createRequest')
            ->with(
                RequestInterface::POST,
                self::BASE_URL.'/ordermanagement/v1/orders/'.self::TRANSACTION_REF.'/captures',
                ['Content-Type' => 'application/json'],
                json_encode($inputData),
                ['auth' => [self::USERNAME, self::SECRET]]
            )->andReturn($request);

        $this->setExpectedGetRequest(
            $expectedData,
            self::BASE_URL.'/ordermanagement/v1/orders/'.self::TRANSACTION_REF.'/captures/'.self::CAPTURE_ID
        );

        $this->captureRequest->initialize([
            'base_url' => self::BASE_URL,
            'username' => self::USERNAME,
            'secret' => self::SECRET,
            'transactionReference' => self::TRANSACTION_REF,
        ]);

        $captureResponse = $this->captureRequest->sendData($inputData);

        self::assertInstanceOf(CaptureResponse::class, $captureResponse);
        self::assertSame($expectedData, $captureResponse->getData());
    }
}
