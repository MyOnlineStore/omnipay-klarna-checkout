<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AcknowledgeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AcknowledgeResponse;

class AcknowledgeRequestTest extends RequestTestCase
{
    /**
     * @var AcknowledgeRequest
     */
    private $acknowledgeRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->acknowledgeRequest = new AcknowledgeRequest($this->httpClient, $this->getHttpRequest());
    }

    public function testGetData()
    {
        $this->acknowledgeRequest->initialize(['transactionReference' => 'foo']);

        self::assertNull($this->acknowledgeRequest->getData());
    }

    public function testSendData()
    {
        $inputData = ['request-data' => 'yey?'];
        $expectedData = [];

        $this->setExpectedPostRequest(
            $inputData,
            $expectedData,
            self::BASE_URL.'/ordermanagement/v1/orders/foo/acknowledge'
        );

        $this->acknowledgeRequest->initialize([
            'base_url' => self::BASE_URL,
            'username' => self::USERNAME,
            'secret' => self::SECRET,
            'transactionReference' => 'foo',
        ]);

        $acknowledgeResponse = $this->acknowledgeRequest->sendData($inputData);

        self::assertInstanceOf(AcknowledgeResponse::class, $acknowledgeResponse);
        self::assertSame($expectedData, $acknowledgeResponse->getData());
    }
}
