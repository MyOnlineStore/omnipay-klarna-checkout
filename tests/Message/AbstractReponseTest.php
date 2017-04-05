<?php
namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AbstractResponse;
use Omnipay\Tests\TestCase;

class AbstractResponseTest extends TestCase
{
    public function testGetTransactionReferenceReturnsIdFromOrder()
    {
        $response = $this->getMockForAbstractClass(
            AbstractResponse::class,
            [$this->getMockRequest(), ['order_id' => 'foo']]
        );

        self::assertEquals('foo', $response->getTransactionReference());
    }
}
