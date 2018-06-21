<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout;

use MyOnlineStore\Omnipay\KlarnaCheckout\AuthenticationRequestOptionProvider;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AbstractRequest;

final class AuthenticationRequestOptionProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testWillReturnOptions()
    {
        $request = $this->getMockBuilder(AbstractRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects(self::once())->method('getUsername')->will(self::returnValue('foobar'));
        $request->expects(self::once())->method('getSecret')->will(self::returnValue('barbaz'));

        self::assertEquals(
            ['auth' => ['foobar', 'barbaz']],
            (new AuthenticationRequestOptionProvider())->getOptions($request)
        );
    }
}
