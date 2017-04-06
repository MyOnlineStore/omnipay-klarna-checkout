<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AbstractResponse;
use Omnipay\Tests\TestCase;

class AbstractResponseTest extends TestCase
{
    public function testGetTransactionReferenceReturnsIdFromOrder()
    {
        /** @var AbstractResponse $response */
        $response = $this->getMockForAbstractClass(
            AbstractResponse::class,
            [$this->getMockRequest(), ['order_id' => 'foo']]
        );

        self::assertEquals('foo', $response->getTransactionReference());
    }

    public function testIsSuccessfulWillReturnFalseIfResponseContainsErrors()
    {
        /** @var AbstractResponse $response */
        $response = $this->getMockForAbstractClass(
            AbstractResponse::class,
            [$this->getMockRequest(), ['error_code' => 'foo']]
        );

        self::assertFalse($response->isSuccessful());
    }

    public function testIsSuccessfulWillReturnTrueIfResponseContainsNoErrors()
    {
        /** @var AbstractResponse $response */
        $response = $this->getMockForAbstractClass(AbstractResponse::class, [$this->getMockRequest(), []]);

        self::assertTrue($response->isSuccessful());
    }

    public function testGetMessageWillReturnErrorMessage()
    {
        /** @var AbstractResponse $response */
        $response = $this->getMockForAbstractClass(
            AbstractResponse::class,
            [$this->getMockRequest(), ['error_message' => 'oh noes!']]
        );

        self::assertSame('oh noes!', $response->getMessage());
    }

    public function testGetMessageWillReturnNullIfResponseContainsNoErrorMessage()
    {
        /** @var AbstractResponse $response */
        $response = $this->getMockForAbstractClass(AbstractResponse::class, [$this->getMockRequest(), []]);

        self::assertNull($response->getMessage());
    }

    public function testGetCodeWillReturnErrorCode()
    {
        /** @var AbstractResponse $response */
        $response = $this->getMockForAbstractClass(
            AbstractResponse::class,
            [$this->getMockRequest(), ['error_code' => 'oh_noes']]
        );

        self::assertSame('oh_noes', $response->getCode());
    }

    public function testGetCodeWillReturnNullIfResponseContainsNoErrorCode()
    {
        /** @var AbstractResponse $response */
        $response = $this->getMockForAbstractClass(AbstractResponse::class, [$this->getMockRequest(), []]);

        self::assertNull($response->getCode());
    }
}
