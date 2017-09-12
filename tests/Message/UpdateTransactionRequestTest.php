<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\UpdateTransactionRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\UpdateTransactionResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class UpdateTransactionRequestTest extends RequestTestCase
{
    use ItemDataTestTrait;

    const TRANSACTION_REFERENCE = 1234;

    /**
     * @var UpdateTransactionRequest
     */
    private $updateTransactionRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->updateTransactionRequest = new UpdateTransactionRequest($this->httpClient, $this->getHttpRequest());
    }

    public function testGetDataWillThrowExceptionForInvalidRequest()
    {
        $this->updateTransactionRequest->initialize([]);

        $this->setExpectedException(InvalidRequestException::class);
        $this->updateTransactionRequest->getData();
    }

    public function testGetDataWillReturnCorrectData()
    {
        $this->updateTransactionRequest->initialize([
            'locale' => 'nl_NL',
            'amount' => '100.00',
            'tax_amount' => 21,
            'currency' => 'EUR',
            'transactionReference' => self::TRANSACTION_REFERENCE,
        ]);
        $this->updateTransactionRequest->setItems([$this->getItemMock()]);

        self::assertEquals(
            [
                'locale' => 'nl-NL',
                'order_amount' => 10000,
                'order_tax_amount' => 2100,
                'order_lines' => [$this->getExpectedOrderLine()],
                'purchase_country' => 'NL',
                'purchase_currency' => 'EUR',
            ],
            $this->updateTransactionRequest->getData()
        );
    }

    public function testSendDataWillCreateOrderAndReturnResponse()
    {
        $inputData = ['request-data' => 'yey?'];
        $expectedData = [];

        $this->setExpectedPostRequest(
            $inputData,
            $expectedData,
            sprintf('%s/checkout/v3/orders/%s', self::BASE_URL, self::TRANSACTION_REFERENCE)
        );

        $this->updateTransactionRequest->initialize([
            'base_url' => self::BASE_URL,
            'merchant_id' => self::MERCHANT_ID,
            'secret' => self::SECRET,
            'transactionReference' => self::TRANSACTION_REFERENCE,
        ]);

        $updateTransactionResponse = $this->updateTransactionRequest->sendData($inputData);

        self::assertInstanceOf(UpdateTransactionResponse::class, $updateTransactionResponse);
        self::assertSame($expectedData, $updateTransactionResponse->getData());
    }
}
