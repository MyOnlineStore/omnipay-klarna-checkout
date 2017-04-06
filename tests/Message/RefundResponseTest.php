<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\RefundResponse;
use Omnipay\Tests\TestCase;

class RefundResponseTest extends TestCase
{
    public function testIsSuccessfulWillReturnTrue()
    {
        $response = new RefundResponse($this->getMockRequest(), [], []);
        self::assertTrue($response->isSuccessful());
    }
}
