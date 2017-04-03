<?php
namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

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
        self::assertEquals('GET', $response->getRedirectMethod());
        self::assertEquals('localhost/return', $response->getRedirectUrl());
        self::assertTrue($response->isRedirect());
    }

    public function testIsSuccessfulWillReturnWhetherResponseStatusIsIncomplete()
    {
        $failResponse = new AuthorizeResponse($this->getMockRequest(), ['status' => 'checkout_incomplete']);
        $successResponse = new AuthorizeResponse($this->getMockRequest(), ['status' => 'all_is_well']);

        self::assertFalse($failResponse->isSuccessful());
        self::assertTrue($successResponse->isSuccessful());
    }
}
