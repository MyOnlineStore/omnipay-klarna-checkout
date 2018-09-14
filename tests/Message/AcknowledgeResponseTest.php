<?php
declare(strict_types=1);

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
    public function responseCodeProvider(): array
    {
        return [[204, true], [403, false], [404, false]];
    }

    public function testGetCodeWillReturnNullIfResponseContainsNoErrorCode()
    {
        $request = $this->createMock(RequestInterface::class);
        $acknowledgeResponse = new AcknowledgeResponse($request, [], 200);

        self::assertNull($acknowledgeResponse->getCode());
    }

    public function testGetMessageWillReturnErrorMessage()
    {
        $request = $this->createMock(RequestInterface::class);
        $acknowledgeResponse = new AcknowledgeResponse($request, ['error_message' => 'oh noes!'], 200);

        self::assertSame('oh noes!', $acknowledgeResponse->getMessage());
    }

    public function testGetTransactionReferenceReturnsIdFromOrder()
    {
        $request = $this->createMock(RequestInterface::class);
        $acknowledgeResponse = new AcknowledgeResponse($request, ['order_id' => 'foo'], 200);

        self::assertEquals('foo', $acknowledgeResponse->getTransactionReference());
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

        $acknowledgeResponse = new AcknowledgeResponse($request, [], $responseCode);

        self::assertEquals($expectedResult, $acknowledgeResponse->isSuccessful());
    }

    public function testIsSuccessfulWillReturnFalseWhenErrorIsFound()
    {
        $request = $this->createMock(RequestInterface::class);

        $acknowledgeResponse = new AcknowledgeResponse($request, ['error_code' => 'foobar'], 200);

        self::assertFalse($acknowledgeResponse->isSuccessful());
    }
}
