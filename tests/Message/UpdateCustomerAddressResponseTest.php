<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\UpdateCustomerAddressResponse;
use Omnipay\Tests\TestCase;

final class UpdateCustomerAddressResponseTest extends TestCase
{
    /**
     * @return array
     */
    public function responseDataProvider()
    {
        return [
            [['error_code' => 'oh_noes'], false, 403],
            [[], false, 200],
            [[], true, 204],
        ];
    }

    public function testGetters()
    {
        $responseData = ['order_id' => 'foo'];
        $response = new UpdateCustomerAddressResponse($this->getMockRequest(), $responseData, '403');

        self::assertSame('foo', $response->getTransactionReference());
    }

    /**
     * @dataProvider responseDataProvider
     *
     * @param array $responseData
     * @param bool  $expected
     * @param int   $reponseCode
     */
    public function testIsSuccessfulWillReturnWhetherResponseIsSuccessfull($responseData, $expected, $reponseCode)
    {
        $response = new UpdateCustomerAddressResponse($this->getMockRequest(), $responseData, $reponseCode);

        self::assertEquals($expected, $response->isSuccessful());
    }
}
