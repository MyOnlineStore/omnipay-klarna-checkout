<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\CaptureResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Tests\TestCase;

final class CaptureResponseTest extends TestCase
{
    /**
     * All possible Klarna response codes for this class
     *
     * @see https://developers.klarna.com/api/#order-management-api-create-capture
     *
     * @return array
     */
    public function responseCodeProvider(): array
    {
        return [[201, true], [403, false], [404, false]];
    }

    public function testGetTransactionReferenceReturnsIdFromOrder()
    {
        $request = $this->createMock(RequestInterface::class);

        $response = new CaptureResponse($request, [], 'foo', 201);

        self::assertSame('foo', $response->getTransactionReference());
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

        $captureResponse = new CaptureResponse($request, [], '123', $responseCode);

        self::assertEquals($expectedResult, $captureResponse->isSuccessful());
    }
}
