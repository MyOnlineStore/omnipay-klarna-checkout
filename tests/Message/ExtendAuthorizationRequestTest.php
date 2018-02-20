<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\ExtendAuthorizationRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\ExtendAuthorizationResponse;
use Omnipay\Common\Exception\InvalidRequestException;

final class ExtendAuthorizationRequestTest extends RequestTestCase
{
    /**
     * @var ExtendAuthorizationRequest
     */
    private $extendAuthorizationRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->extendAuthorizationRequest = new ExtendAuthorizationRequest($this->httpClient, $this->getHttpRequest());
    }

    public function testGetDataThrowsExceptionWhenMissingTransactionReference()
    {
        $this->setExpectedException(InvalidRequestException::class);

        $this->extendAuthorizationRequest->initialize([]);
        $this->extendAuthorizationRequest->getData();
    }

    public function testGetDataWithInvalidDataWillReturnNull()
    {
        $this->extendAuthorizationRequest->initialize(['transactionReference' => 'foo']);

        self::assertNull($this->extendAuthorizationRequest->getData());
    }

    public function testSendDataWillWillSendDataToKlarnaEndPointAndReturnCorrectResponse()
    {
        $this->setExpectedPostRequest(
            [],
            ['hello' => 'world'],
            sprintf(
                '%s/ordermanagement/v1/orders/%s/extend-authorization-time',
                self::BASE_URL,
                'foo'
            )
        );

        $this->extendAuthorizationRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'merchant_id' => self::USERNAME,
                'secret' => self::SECRET,
                'transactionReference' => 'foo',
            ]
        );

        $extendAuthorizationResponse = $this->extendAuthorizationRequest->sendData([]);

        self::assertInstanceOf(ExtendAuthorizationResponse::class, $extendAuthorizationResponse);
        self::assertSame('foo', $extendAuthorizationResponse->getTransactionReference());
        self::assertSame(
            [
                'hello' => 'world',
                'order_id' => 'foo',
            ],
            $extendAuthorizationResponse->getData()
        );
    }
}
