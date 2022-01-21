<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AcknowledgeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AcknowledgeResponse;

final class AcknowledgeRequestTest extends RequestTestCase
{
    /** @var AcknowledgeRequest */
    private $acknowledgeRequest;

    protected function setUp(): void
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

        $response = $this->setExpectedPostRequest(
            $inputData,
            $expectedData,
            self::BASE_URL . '/ordermanagement/v1/orders/foo/acknowledge'
        );

        $response->expects(self::once())->method('getStatusCode')->willReturn(204);

        $this->acknowledgeRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'username' => self::USERNAME,
                'secret' => self::SECRET,
                'transactionReference' => 'foo',
            ]
        );

        $acknowledgeResponse = $this->acknowledgeRequest->sendData($inputData);

        self::assertInstanceOf(AcknowledgeResponse::class, $acknowledgeResponse);
        self::assertSame($expectedData, $acknowledgeResponse->getData());
    }
}
