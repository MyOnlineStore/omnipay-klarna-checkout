<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\RefundResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Tests\TestCase;

final class RefundResponseTest extends TestCase
{
    /**
     * All possible Klarna response codes for this class
     *
     * @see https://developers.klarna.com/api/#order-management-api-refund
     *
     * @return array
     */
    public function responseCodeProvider()
    {
        return [[201, true], [403, false], [404, false]];
    }

    public function testGetCommonValuesReturnCorrectValues()
    {
        $request = $this->getMock(RequestInterface::class);

        $response = new RefundResponse($request, [], 201);

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
        $request = $this->getMock(RequestInterface::class);

        $captureResponse = new RefundResponse($request, [], $responseCode);

        self::assertEquals($expectedResult, $captureResponse->isSuccessful());
    }
}
