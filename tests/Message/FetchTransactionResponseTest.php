<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Tests\TestCase;

class FetchTransactionResponseTest extends TestCase
{
    /**
     * @return array
     */
    public function responseDataProvider(): array
    {
        return [
            [['checkout' => ['error_code' => 'oh_noes']], false],
            [[], false],
            [['checkout' => ['status' => 'all_is_well']], true],
            [['management' => ['error_code' => 'oh_noes']], false],
            [['management' => ['status' => 'all_is_well']], true],
        ];
    }

    public function testGetTransactionReferenceForCheckoutTransaction()
    {
        $request = $this->createMock(RequestInterface::class);

        $responseData = ['checkout' => ['order_id' => 'foo']];
        $response = new FetchTransactionResponse($request, $responseData);

        self::assertSame($responseData['checkout']['order_id'], $response->getTransactionReference());
    }

    public function testGetTransactionReferenceForManagementTransaction()
    {
        $request = $this->createMock(RequestInterface::class);

        $responseData = ['management' => ['order_id' => 'foo']];
        $response = new FetchTransactionResponse($request, $responseData);

        self::assertSame($responseData['management']['order_id'], $response->getTransactionReference());
    }

    /**
     * @dataProvider responseDataProvider
     *
     * @param array $responseData
     * @param bool  $expected
     */
    public function testIsSuccessfulWillReturnWhetherResponseIsSuccessfull($responseData, $expected)
    {
        $request = $this->createMock(RequestInterface::class);

        $response = new FetchTransactionResponse($request, $responseData);

        self::assertEquals($expected, $response->isSuccessful());
    }
}
