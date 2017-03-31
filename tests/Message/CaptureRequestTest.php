<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\CaptureRequest;
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
}
