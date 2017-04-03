<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\VoidResponse;
use Omnipay\Tests\TestCase;

class VoidResponseTest extends TestCase
{
    public function testGetTransactionReferenceReturnsIdFromOrder()
    {
        $response = new VoidResponse($this->getMockRequest(), ['order_id' => 'foo']);

        self::assertEquals('foo', $response->getTransactionReference());
    }

    public function testIsSuccessfulWillReturnWhetherResponseContainsErrors()
    {
        $response = new VoidResponse($this->getMockRequest(), []);
        self::assertTrue($response->isSuccessful());
    }
}
