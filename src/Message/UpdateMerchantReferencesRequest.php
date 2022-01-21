<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

final class UpdateMerchantReferencesRequest extends AbstractOrderRequest
{
    public function getData()
    {
        $this->validate('transactionReference');

        return [
            'merchant_reference1' => $this->getMerchantReference1(),
            'merchant_reference2' => $this->getMerchantReference2(),
        ];
    }

    public function sendData($data)
    {
        return new UpdateMerchantReferencesResponse(
            $this,
            $this->getResponseBody(
                $this->sendRequest(
                    'PATCH',
                    \sprintf('/ordermanagement/v1/orders/%s/merchant-references', $this->getTransactionReference()),
                    $data
                )
            )
        );
    }
}
