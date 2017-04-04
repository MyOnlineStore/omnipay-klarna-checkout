<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionResponse;
use Omnipay\Tests\TestCase;

class FetchTransactionResponseTest extends TestCase
{
    /**
     * @return array
     */
    public function responseDataProvider()
    {
        return [
            [['error_code' => 'oh_noes'], false],
            [[], false],
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
        $response = new FetchTransactionResponse($this->getMockRequest(), $responseData);

        self::assertEquals($expected, $response->isSuccessful());
    }
}
