<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AbstractRequest;

final class AuthenticationRequestOptionProvider
{
    /**
     * @param AbstractRequest $request
     *
     * @return array
     */
    public function getOptions(AbstractRequest $request)
    {
        return [
            'auth' => [
                $request->getUsername(),
                $request->getSecret(),
            ],
        ];
    }
}
