<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\ExtendAuthorizationResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Tests\TestCase;

final class ExtendAuthorizationResponseTest extends TestCase
{
    /**
     * @return array
     */
    public function responseDataProvider(): array
    {
        return [
            [['error_code' => 'oh_noes'], false],
            [[], true],
        ];
    }

    public function testGetters()
    {
        $request = $this->createMock(RequestInterface::class);

        $responseData = ['order_id' => 'foo'];
        $response = new ExtendAuthorizationResponse($request, $responseData);

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
        $request = $this->createMock(RequestInterface::class);
        $response = new ExtendAuthorizationResponse($request, $responseData);

        self::assertEquals($expected, $response->isSuccessful());
    }
}
