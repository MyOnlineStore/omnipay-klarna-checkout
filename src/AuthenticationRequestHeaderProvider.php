<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AbstractRequest;

final class AuthenticationRequestHeaderProvider
{
    public function getHeaders(AbstractRequest $request): array
    {
        return [
            'Authorization' => \sprintf(
                'Basic %s',
                \base64_encode(
                    \sprintf(
                        '%s:%s',
                        $request->getUsername(),
                        $request->getSecret()
                    )
                )
            ),
        ];
    }
}
