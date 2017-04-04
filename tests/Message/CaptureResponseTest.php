<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\CaptureResponse;
use Omnipay\Tests\TestCase;

class CaptureResponseTest extends TestCase
{
    public function testGetTransactionReferenceReturnsIdFromOrder()
    {
        $response = new CaptureResponse($this->getMockRequest(), [], 'foo');

        self::assertEquals('foo', $response->getTransactionReference());
    }
}
