<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout;

use Klarna\Rest\Transport\ConnectorInterface;
use MyOnlineStore\Omnipay\KlarnaCheckout\Gateway;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AuthorizeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\CaptureRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionRequest;
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

    public function testInitialisationForUSRegion()
    {
        $this->gateway->initialize([
            'api_region' => Gateway::API_VERSION_NORTH_AMERICA,
            'testMode' => false
        ]);
        self::assertEquals(
            ConnectorInterface::NA_BASE_URL,
            $this->gateway->getParameter('connector')->getClient()->getBaseUrl()
        );

        $this->gateway->initialize([
            'api_region' => Gateway::API_VERSION_NORTH_AMERICA,
            'testMode' => true
        ]);
        self::assertEquals(
            ConnectorInterface::NA_TEST_BASE_URL,
            $this->gateway->getParameter('connector')->getClient()->getBaseUrl()
        );
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
}
