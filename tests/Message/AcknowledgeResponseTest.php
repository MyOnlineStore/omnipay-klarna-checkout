<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AcknowledgeResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Tests\TestCase;

final class AcknowledgeResponseTest extends TestCase
{
    /**
     * All possible Klarna response codes for this class
     *
     * @see https://developers.klarna.com/api/#order-management-api-acknowledge-order
     *
     * @return array
     */
    public function responseCodeProvider()
    {
        return [[204, true], [403, false], [404, false]];
    }

    /**
     * @dataProvider responseCodeProvider
     *
     * @param string $responseCode
     * @param bool $expectedResult
     */
    public function testIsSuccessfulWillReturnCorrectStateWithResponseCode($responseCode, $expectedResult)
    {
        $request = $this->getMock(RequestInterface::class);

        $acknowledgeResponse = new AcknowledgeResponse($request, [], $responseCode);

        self::assertEquals($expectedResult, $acknowledgeResponse->isSuccessful());
    }

    public function testIsSuccessfulWillReturnFalseWhenErrorIsFound()
    {
        $request = $this->getMock(RequestInterface::class);

        $acknowledgeResponse = new AcknowledgeResponse($request, ['error_code' => 'foobar'], 200);

        self::assertFalse($acknowledgeResponse->isSuccessful());
    }
}
