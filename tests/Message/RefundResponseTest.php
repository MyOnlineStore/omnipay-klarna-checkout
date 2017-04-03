<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\RefundResponse;
use Omnipay\Tests\TestCase;

class RefundResponseTest extends TestCase
{
    public function testGetTransactionReferenceReturnsIdFromOrder()
    {
        $response = new RefundResponse($this->getMockRequest(), ['order_id' => 'foo']);

        self::assertEquals('foo', $response->getTransactionReference());
    }

    public function testIsSuccessfulWillReturnTrue()
    {
        $response = new RefundResponse($this->getMockRequest(), [], []);
        self::assertTrue($response->isSuccessful());
    }
}
