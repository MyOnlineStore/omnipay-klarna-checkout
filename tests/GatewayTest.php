<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout;

use MyOnlineStore\Omnipay\KlarnaCheckout\Gateway;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AcknowledgeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AuthorizeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\CaptureRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\RefundRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\VoidRequest;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;

    protected function setUp()
    {
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    /**
     * @return array
     */
    public function baseUrlDataProvider()
    {
        return [
            [true, Gateway::API_VERSION_EUROPE, Gateway::EU_TEST_BASE_URL],
            [false, Gateway::API_VERSION_EUROPE, Gateway::EU_BASE_URL],
            [true, Gateway::API_VERSION_NORTH_AMERICA, Gateway::NA_TEST_BASE_URL],
            [false, Gateway::API_VERSION_NORTH_AMERICA, Gateway::NA_BASE_URL],
        ];
    }

    /**
     * @dataProvider baseUrlDataProvider
     *
     * @param bool   $testMode
     * @param string $region
     * @param string $expectedUrl
     */
    public function testInitialisationWillSetCorrectBaseUrl($testMode, $region, $expectedUrl)
    {
        $this->gateway->initialize(['testMode' => $testMode, 'api_region' => $region]);
        self::assertEquals($expectedUrl, $this->gateway->getParameter('base_url'));
    }

    public function testAcknowledge()
    {
        $this->assertInstanceOf(AcknowledgeRequest::class, $this->gateway->acknowledge());
    }

    public function testAuthorize()
    {
        $this->assertInstanceOf(AuthorizeRequest::class, $this->gateway->authorize());
    }

    public function testCapture()
    {
        $this->assertInstanceOf(CaptureRequest::class, $this->gateway->capture());
    }

    public function testFetchTransaction()
    {
        $this->assertInstanceOf(FetchTransactionRequest::class, $this->gateway->fetchTransaction());
    }

    public function testRefund()
    {
        $this->assertInstanceOf(RefundRequest::class, $this->gateway->refund());
    }

    public function testVoid()
    {
        $this->assertInstanceOf(VoidRequest::class, $this->gateway->void());
    }
}
