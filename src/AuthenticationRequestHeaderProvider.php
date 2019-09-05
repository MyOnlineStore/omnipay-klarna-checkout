<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AbstractRequest;

final class AuthenticationRequestHeaderProvider
{
    /**
     * @param AbstractRequest $request
     *
     * @return array
     */
    public static function getHeaders(AbstractRequest $request)
    {
        return [
            'Authorization' => sprintf(
                'Basic %s',
                base64_encode(
                    sprintf(
                        '%s:%s',
                        $request->getUsername(),
                        $request->getSecret()
                    )
                )
            ),
        ];
    }
}
