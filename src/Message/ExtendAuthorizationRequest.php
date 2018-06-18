<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Exception\InvalidRequestException;

final class ExtendAuthorizationRequest extends AbstractRequest
{
    /**
     * @return null
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionReference');

        return null;
    }

    /**
     * @param mixed $data
     *
     * @return ExtendAuthorizationResponse
     */
    public function sendData($data): ExtendAuthorizationResponse
    {
        $responseBody = $this->getResponseBody(
            $this->sendRequest(
                'POST',
                sprintf('/ordermanagement/v1/orders/%s/extend-authorization-time', $this->getTransactionReference()),
                $data
            )
        );

        return new ExtendAuthorizationResponse(
            $this,
            \array_merge(
                $responseBody,
                ['order_id' => $this->getTransactionReference()]
            )
        );
    }
}
