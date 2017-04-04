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

    /**
     * @return array
     */
    public function responseDataProvider()
    {
        return [
            [['error_code' => 'oh_noes'], false],
            [['status' => 'checkout_incomplete'], false],
            [['status' => 'all_is_well'], true],
        ];
    }

    /**
     * @dataProvider responseDataProvider
     *
     * @param array $responseData
     * @param bool  $expected
     */
    public function testIsSuccessfulWillReturnWhetherResponseIsSuccessfull($responseData, $expected)
    {
        $response = new AuthorizeResponse($this->getMockRequest(), $responseData);

        self::assertEquals($expected, $response->isSuccessful());
    }
}
