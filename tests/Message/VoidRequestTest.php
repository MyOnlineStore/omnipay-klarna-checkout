<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\VoidRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\VoidResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class VoidRequestTest extends RequestTestCase
{
    const TRANSACTION_REF = 'foo';

    /**
     * @var VoidRequest
     */
    private $voidRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->voidRequest = new VoidRequest($this->httpClient, $this->getHttpRequest());
    }

    public function testGetDataWillThrowExceptionForInvalidRequest()
    {
        $this->voidRequest->initialize([]);

        $this->setExpectedException(InvalidRequestException::class);
        $this->voidRequest->getData();
    }

    public function testGetDataWillReturnCorrectData()
    {
        $this->voidRequest->initialize(['transactionReference' => 'foo']);

        self::assertEquals([], $this->voidRequest->getData());
    }

    /**
     * @return array
     */
    public function voidRequestCaptureDataProvider()
    {
        return [
            [[], '/cancel'],
            [[['capture-id' => 1]], '/release-remaining-authorization']
        ];
    }

    /**
     * @dataProvider voidRequestCaptureDataProvider
     *
     * @param array  $captures
     * @param string $expectedPostRoute
     */
    public function testSendDataWillVoidOrderAndReturnResponse(array $captures, $expectedPostRoute)
    {
        $inputData = ['request-data' => 'yey?'];
        $expectedData = [];

        $this->setExpectedGetRequest(
            ['captures' => $captures],
            self::BASE_URL.'/ordermanagement/v1/orders/'.self::TRANSACTION_REF
        );

        $this->setExpectedPostRequest(
            $inputData,
            $expectedData,
            self::BASE_URL.'/ordermanagement/v1/orders/'.self::TRANSACTION_REF.$expectedPostRoute
        );

        $this->voidRequest->initialize([
            'base_url' => self::BASE_URL,
            'merchant_id' => self::MERCHANT_ID,
            'secret' => self::SECRET,
            'transactionReference' => self::TRANSACTION_REF,
        ]);

        $voidResponse = $this->voidRequest->sendData($inputData);

        self::assertInstanceOf(VoidResponse::class, $voidResponse);
        self::assertSame($expectedData, $voidResponse->getData());
    }
}
