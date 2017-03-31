<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\CaptureResponse;
use Omnipay\Tests\TestCase;

class CaptureResponseTest extends TestCase
{
    public function testGetTransactionReferenceReturnsIdFromOrder()
    {
        $response = new CaptureResponse($this->getMockRequest(), [], ['order_id' => 'foo']);

        self::assertEquals('foo', $response->getTransactionReference());
    }

    public function testIsSuccessfulWillReturnWhetherResponseContainsErrors()
    {
        $failResponse = new CaptureResponse($this->getMockRequest(), ['error_code' => 'oh noes!'], []);
        $successResponse = new CaptureResponse($this->getMockRequest(), [], []);

        self::assertFalse($failResponse->isSuccessful());
        self::assertTrue($successResponse->isSuccessful());
    }
}