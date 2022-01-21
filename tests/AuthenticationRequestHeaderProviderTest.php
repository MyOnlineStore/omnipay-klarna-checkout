<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout;

use MyOnlineStore\Omnipay\KlarnaCheckout\AuthenticationRequestHeaderProvider;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AbstractRequest;
use PHPUnit\Framework\TestCase;

final class AuthenticationRequestHeaderProviderTest extends TestCase
{
    public function testWithNoMerchantIdSetWillReturnHeadersWithUsername()
    {
        $request = $this->createMock(AbstractRequest::class);

        $userName = 'foobar';
        $request->expects(self::once())->method('getUsername')->willReturn($userName);
        $secret = 'barbaz';
        $request->expects(self::once())->method('getSecret')->willReturn($secret);

        self::assertEquals(
            [
                'Authorization' => \sprintf(
                    'Basic %s',
                    \base64_encode(
                        \sprintf(
                            '%s:%s',
                            $userName,
                            $secret
                        )
                    )
                ),
            ],
            (new AuthenticationRequestHeaderProvider())->getHeaders($request)
        );
    }
}
