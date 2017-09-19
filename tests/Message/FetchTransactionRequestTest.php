<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class FetchTransactionRequestTest extends RequestTestCase
{
    /**
     * @var FetchTransactionRequest
     */
    private $fetchTransactionRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->fetchTransactionRequest = new FetchTransactionRequest($this->httpClient, $this->getHttpRequest());
    }

    public function testGetDataReturnsNull()
    {
        $this->fetchTransactionRequest->initialize(['transactionReference' => 'foo']);

        self::assertNull($this->fetchTransactionRequest->getData());
    }

    public function testGetDataThrowsExceptionWhenMissingTransactionReference()
    {
        $this->setExpectedException(InvalidRequestException::class);

        $this->fetchTransactionRequest->initialize([]);
        $this->fetchTransactionRequest->getData();
    }

    public function testSendDataWillReturnResponseFromCheckoutApiForUnknownOrder()
    {
        $expectedCheckoutData = ['response-data' => 'nay!'];
        $this->setExpectedGetRequest($expectedCheckoutData, self::BASE_URL.'/checkout/v3/orders/foo');

        $this->fetchTransactionRequest->initialize([
            'base_url' => self::BASE_URL,
            'merchant_id' => self::MERCHANT_ID,
            'secret' => self::SECRET,
            'transactionReference' => 'foo',
        ]);

        $fetchResponse = $this->fetchTransactionRequest->sendData([]);

        self::assertInstanceOf(FetchTransactionResponse::class, $fetchResponse);
        self::assertSame(['checkout' => $expectedCheckoutData], $fetchResponse->getData());
    }

    public function testSendDataWillReturnResponseFromCheckoutApiForIncompleteOrder()
    {
        $expectedCheckoutData = ['status' => 'checkout_incomplete'];
        $this->setExpectedGetRequest($expectedCheckoutData, self::BASE_URL.'/checkout/v3/orders/foo');

        $this->fetchTransactionRequest->initialize([
            'base_url' => self::BASE_URL,
            'merchant_id' => self::MERCHANT_ID,
            'secret' => self::SECRET,
            'transactionReference' => 'foo',
        ]);

        $fetchResponse = $this->fetchTransactionRequest->sendData([]);

        self::assertInstanceOf(FetchTransactionResponse::class, $fetchResponse);
        self::assertSame(['checkout' => $expectedCheckoutData], $fetchResponse->getData());
    }

    public function testSendDataWillReturnResponseFromManagementApiForCompleteOrder()
    {
        $expectedCheckoutData = ['status' => 'checkout_complete'];
        $expectedManagementData = ['response-data' => 'yay!'];

        $this->setExpectedGetRequest($expectedCheckoutData, self::BASE_URL.'/checkout/v3/orders/foo');
        $this->setExpectedGetRequest($expectedManagementData, self::BASE_URL.'/ordermanagement/v1/orders/foo');

        $this->fetchTransactionRequest->initialize([
            'base_url' => self::BASE_URL,
            'merchant_id' => self::MERCHANT_ID,
            'secret' => self::SECRET,
            'transactionReference' => 'foo',
        ]);

        $fetchResponse = $this->fetchTransactionRequest->sendData([]);

        self::assertInstanceOf(FetchTransactionResponse::class, $fetchResponse);
        self::assertSame(
            ['checkout' => $expectedCheckoutData, 'management' => $expectedManagementData],
            $fetchResponse->getData()
        );
    }
}
