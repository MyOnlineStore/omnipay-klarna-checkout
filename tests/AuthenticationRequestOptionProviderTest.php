<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout;

use MyOnlineStore\Omnipay\KlarnaCheckout\AuthenticationRequestOptionProvider;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AbstractRequest;

final class AuthenticationRequestOptionProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testWithMerchantIdSetWillReturnOptionsWithMerchantId()
    {
        $request = $this->getMockBuilder(AbstractRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects(self::exactly(2))->method('getMerchantId')->will(self::returnValue('foobar'));
        $request->expects(self::once())->method('getSecret')->will(self::returnValue('barbaz'));

        self::assertEquals(
            ['auth' => ['foobar', 'barbaz']],
            (new AuthenticationRequestOptionProvider())->getOptions($request)
        );
    }

    public function testWithNoMerchantIdSetWillReturnOptionsWithUsername()
    {
        $request = $this->getMockBuilder(AbstractRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects(self::once())->method('getMerchantId')->will(self::returnValue(null));
        $request->expects(self::once())->method('getUsername')->will(self::returnValue('foobar'));
        $request->expects(self::once())->method('getSecret')->will(self::returnValue('barbaz'));

        self::assertEquals(
            ['auth' => ['foobar', 'barbaz']],
            (new AuthenticationRequestOptionProvider())->getOptions($request)
        );
    }
}
