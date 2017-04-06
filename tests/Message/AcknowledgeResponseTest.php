<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AcknowledgeResponse;
use Omnipay\Tests\TestCase;

class AcknowledgeResponseTest extends TestCase
{
    public function testIsSuccessfulWillReturnTrue()
    {
        $response = new AcknowledgeResponse($this->getMockRequest(), []);
        self::assertTrue($response->isSuccessful());
    }
}
