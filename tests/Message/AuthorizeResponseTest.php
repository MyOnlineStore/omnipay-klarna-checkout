<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AuthorizeResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Tests\TestCase;

class AuthorizeResponseTest extends TestCase
{
    public function testIsSuccessfulWillAlwaysReturnFalse()
    {
        $request = $this->createMock(RequestInterface::class);

        $response = new AuthorizeResponse($request, []);

        self::assertFalse($response->isSuccessful());
    }

    public function testResponseIsNonRedirectWithoutRenderUrl()
    {
        $response = new AuthorizeResponse($this->getMockRequest(), [], null);

        self::assertFalse($response->isRedirect());
    }

    public function testResponseIsRedirectWithRenderUrl()
    {
        $request = $this->createMock(RequestInterface::class);
        $response = new AuthorizeResponse($request, [], 'localhost/return');

        self::assertNull($response->getRedirectData());
        self::assertEquals('GET', $response->getRedirectMethod());
        self::assertEquals('localhost/return', $response->getRedirectUrl());
        self::assertTrue($response->isRedirect());
    }
}
