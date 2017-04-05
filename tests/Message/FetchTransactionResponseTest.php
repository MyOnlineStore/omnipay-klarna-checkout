<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionResponse;
use Omnipay\Tests\TestCase;

class FetchTransactionResponseTest extends TestCase
{
    public function testResponseIsSuccessful()
    {
        $failResponse = new FetchTransactionResponse($this->getMockRequest(), []);
        $successResponse = new FetchTransactionResponse($this->getMockRequest(), ['status' => 'foo']);

        self::assertFalse($failResponse->isSuccessful());
        self::assertTrue($successResponse->isSuccessful());
    }
}
