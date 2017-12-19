<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\Message\RequestInterface;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AuthorizeResponse;
use Omnipay\Tests\TestCase;

class AuthorizeResponseTest extends TestCase
{
    public function testResponseIsNonRedirectWithoutRenderUrl()
    {
        $response = new AuthorizeResponse($this->getMockRequest(), [], null);

        self::assertFalse($response->isRedirect());
    }

    public function testResponseIsRedirectWithRenderUrl()
    {
        $response = new AuthorizeResponse($this->getMockRequest(), [], 'localhost/return');

        self::assertNull($response->getRedirectData());
        self::assertEquals(RequestInterface::GET, $response->getRedirectMethod());
        self::assertEquals('localhost/return', $response->getRedirectUrl());
        self::assertTrue($response->isRedirect());
    }

    public function testIsSuccessfulWillAlwaysReturnFalse()
    {
        $response = new AuthorizeResponse($this->getMockRequest(), []);

        self::assertFalse($response->isSuccessful());
    }
}
