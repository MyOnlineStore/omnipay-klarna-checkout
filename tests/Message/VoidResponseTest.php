<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\VoidResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Tests\TestCase;

final class VoidResponseTest extends TestCase
{
    /**
     * All possible Klarna response codes for this class
     *
     * @see https://developers.klarna.com/api/#order-management-api-cancel-order
     *
     * @return array
     */
    public function responseCodeProvider(): array
    {
        return [[204, true], [403, false]];
    }

    public function testGetCommonValuesReturnCorrectValues()
    {
        $request = $this->createMock(RequestInterface::class);

        $response = new VoidResponse($request, [], 201);

        self::assertSame(201, $response->getStatusCode());
    }

    /**
     * @dataProvider responseCodeProvider
     *
     * @param string $responseCode
     * @param bool   $expectedResult
     */
    public function testIsSuccessfulWillReturnCorrectStateWithResponseCode($responseCode, $expectedResult)
    {
        $request = $this->createMock(RequestInterface::class);

        $captureResponse = new VoidResponse($request, [], $responseCode);

        self::assertEquals($expectedResult, $captureResponse->isSuccessful());
    }
}
