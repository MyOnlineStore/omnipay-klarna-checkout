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
            [['checkout' => ['error_code' => 'oh_noes']], false],
            [[], false],
            [['checkout' => ['status' => 'all_is_well']], true],
        ];
    }

    public function testGetters()
    {
        $responseData = ['checkout' => ['order_id' => 'foo']];
        $response = new FetchTransactionResponse($this->getMockRequest(), $responseData);

        self::assertSame($responseData['checkout']['order_id'], $response->getTransactionReference());
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
