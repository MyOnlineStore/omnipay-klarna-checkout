<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout;

use MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message\RequestTestCase;

trait ExpectedAuthorizationHeaderTrait
{
    /**
     * @return array
     */
    public function getExpectedHeaders(): array
    {
        return [
            'Authorization' => \sprintf(
                'Basic %s',
                \base64_encode(
                    \sprintf(
                        '%s:%s',
                        RequestTestCase::USERNAME,
                        RequestTestCase::SECRET
                    )
                )
            ),
        ];
    }
}
