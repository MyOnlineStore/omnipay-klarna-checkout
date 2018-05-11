<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidRequestException;

final class UpdateCustomerAddressRequest extends AbstractOrderRequest
{
    /**
     * @return null
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionReference', 'billing_address', 'shipping_address');

        return [
            'shipping_address' => $this->getShippingAddress()->toArray(['organization_name']),
            'billing_address' => $this->getBillingAddress()->toArray(['organization_name']),
        ];
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(
            RequestInterface::PATCH,
            sprintf('/ordermanagement/v1/orders/%s/customer-details', $this->getTransactionReference()),
            $data
        );

        return new UpdateCustomerAddressResponse(
            $this,
            \array_merge(
                $this->getResponseBody($response),
                ['order_id' => $this->getTransactionReference()]
            ),
            $response->getStatusCode()
        );
    }
}
