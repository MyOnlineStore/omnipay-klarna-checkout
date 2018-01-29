<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AbstractRequest;

final class AuthenticationRequestOptionProvider
{
    /**
     * @todo fallback to getMerchantId can be removed in next major version
     *
     * @param AbstractRequest $request
     *
     * @return array
     */
    public function getOptions(AbstractRequest $request)
    {
        return [
            'auth' => [
                !empty($request->getMerchantId()) ? $request->getMerchantId() : $request->getUsername(),
                $request->getSecret(),
            ],
        ];
    }
}
