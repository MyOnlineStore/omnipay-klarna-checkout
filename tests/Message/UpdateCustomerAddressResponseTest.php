<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\UpdateCustomerAddressResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Tests\TestCase;

final class UpdateCustomerAddressResponseTest extends TestCase
{
    /**
     * @return array
     */
    public function responseDataProvider(): array
    {
        return [
            [['error_code' => 'oh_noes'], false, 403],
            [[], false, 200],
            [[], true, 204],
        ];
    }

    public function testGetters()
    {
        $request = $this->createMock(RequestInterface::class);

        $responseData = ['order_id' => 'foo'];
        $response = new UpdateCustomerAddressResponse($request, $responseData, '403');

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
        $request = $this->createMock(RequestInterface::class);

        $response = new UpdateCustomerAddressResponse($request, $responseData, $reponseCode);

        self::assertEquals($expected, $response->isSuccessful());
    }
}
