<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AbstractResponse;
use Omnipay\Tests\TestCase;

class AbstractResponseTest extends TestCase
{
    public function testGetTransactionReferenceReturnsIdFromOrder()
    {
        $response = $this->getMockForAbstractClass(
            AbstractResponse::class,
            [$this->getMockRequest(), ['order_id' => 'foo']]
        );

        self::assertEquals('foo', $response->getTransactionReference());
    }

    public function testGetTransactionReferenceReturnsNullIfNoOrderIdExists()
    {
        $response = $this->getMockForAbstractClass(
            AbstractResponse::class,
            [$this->getMockRequest(), []]
        );

        self::assertNull($response->getTransactionReference());
    }

    public function testIsSuccessfulWillReturnFalseIfResponseContainsErrors()
    {
        $response = $this->getMockForAbstractClass(
            AbstractResponse::class,
            [$this->getMockRequest(), ['error_code' => 'foo']]
        );

        self::assertFalse($response->isSuccessful());
    }

    public function testIsSuccessfulWillReturnTrueIfResponseContainsNoErrors()
    {
        $response = $this->getMockForAbstractClass(AbstractResponse::class, [$this->getMockRequest(), []]);

        self::assertTrue($response->isSuccessful());
    }

    public function testGetMessageWillReturnErrorMessage()
    {
        $response = $this->getMockForAbstractClass(
            AbstractResponse::class,
            [$this->getMockRequest(), ['error_message' => 'oh noes!']]
        );

        self::assertSame('oh noes!', $response->getMessage());
    }

    public function testGetMessageWillReturnNullIfResponseContainsNoErrorMessage()
    {
        $response = $this->getMockForAbstractClass(AbstractResponse::class, [$this->getMockRequest(), []]);

        self::assertNull($response->getMessage());
    }

    public function testGetCodeWillReturnErrorCode()
    {
        $response = $this->getMockForAbstractClass(
            AbstractResponse::class,
            [$this->getMockRequest(), ['error_code' => 'oh_noes']]
        );

        self::assertSame('oh_noes', $response->getCode());
    }

    public function testGetCodeWillReturnNullIfResponseContainsNoErrorCode()
    {
        $response = $this->getMockForAbstractClass(AbstractResponse::class, [$this->getMockRequest(), []]);

        self::assertNull($response->getCode());
    }
}
