<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout;

use MyOnlineStore\Omnipay\KlarnaCheckout\Gateway;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AcknowledgeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AuthorizeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\CaptureRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\ExtendAuthorizationRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\RefundRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\UpdateCustomerAddressRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\UpdateMerchantReferencesRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\UpdateTransactionRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\VoidRequest;
use Omnipay\Tests\GatewayTestCase;

final class GatewayTest extends GatewayTestCase
{
    /** @var Gateway */
    protected $gateway;

    protected function setUp(): void
    {
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    /**
     * @return array
     */
    public function baseUrlDataProvider(): array
    {
        return [
            [true, Gateway::API_VERSION_EUROPE, Gateway::EU_TEST_BASE_URL],
            [false, Gateway::API_VERSION_EUROPE, Gateway::EU_BASE_URL],
            [true, Gateway::API_VERSION_NORTH_AMERICA, Gateway::NA_TEST_BASE_URL],
            [false, Gateway::API_VERSION_NORTH_AMERICA, Gateway::NA_BASE_URL],
        ];
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

    public function testExtendAuthorizationWillReturnInstanceOfExtendAuthorization()
    {
        $request = $this->gateway->extendAuthorization(['transactionReference' => 'foobar']);

        $this->assertInstanceOf(ExtendAuthorizationRequest::class, $request);
        self::assertSame('foobar', $request->getTransactionReference());
    }

    public function testFetchTransaction()
    {
        $this->assertInstanceOf(FetchTransactionRequest::class, $this->gateway->fetchTransaction());
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

    /**
     * @dataProvider baseUrlDataProvider
     *
     * @param bool   $testMode
     * @param string $region
     * @param string $expectedUrl
     */
    public function testSetTestModeWillSetCorrectBaseUrl($testMode, $region, $expectedUrl)
    {
        $this->gateway->initialize(['api_region' => $region]);
        $this->gateway->setTestMode($testMode);
        self::assertEquals($expectedUrl, $this->gateway->getParameter('base_url'));
    }

    public function testRefund()
    {
        $this->assertInstanceOf(RefundRequest::class, $this->gateway->refund());
    }

    public function testUpdateCustomerAddress()
    {
        $this->assertInstanceOf(UpdateCustomerAddressRequest::class, $this->gateway->updateCustomerAddress());
    }

    public function testUpdateMerchantReferences()
    {
        $this->assertInstanceOf(UpdateMerchantReferencesRequest::class, $this->gateway->updateMerchantReferences());
    }

    public function testUpdateTransaction()
    {
        $this->assertInstanceOf(UpdateTransactionRequest::class, $this->gateway->updateTransaction());
    }

    public function testVoid()
    {
        $this->assertInstanceOf(VoidRequest::class, $this->gateway->void());
    }
}
