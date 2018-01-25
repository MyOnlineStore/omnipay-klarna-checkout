<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\ExtendAuthorizationResponse;
use Omnipay\Tests\TestCase;

final class ExtendAuthorizationResponseTest extends TestCase
{
    /**
     * @return array
     */
    public function responseDataProvider()
    {
        return [
            [['error_code' => 'oh_noes'], false],
            [[], true],
        ];
    }

    public function testGetters()
    {
        $responseData = ['order_id' => 'foo'];
        $response = new ExtendAuthorizationResponse($this->getMockRequest(), $responseData);

        self::assertSame('foo', $response->getTransactionReference());
    }

    /**
     * @dataProvider responseDataProvider
     *
     * @param array $responseData
     * @param bool  $expected
     */
    public function testIsSuccessfulWillReturnWhetherResponseIsSuccessfull($responseData, $expected)
    {
        $response = new ExtendAuthorizationResponse($this->getMockRequest(), $responseData);

        self::assertEquals($expected, $response->isSuccessful());
    }
}
