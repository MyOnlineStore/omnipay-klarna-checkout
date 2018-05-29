<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidRequestException;

final class UpdateCustomerAddressRequest extends AbstractOrderRequest
{
    /**
     * @return array
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionReference', 'billing_address', 'shipping_address');

        return [
            'shipping_address' => $this->getShippingAddress()->toArray($this->getExcludeEmptyValues()),
            'billing_address' => $this->getBillingAddress()->toArray($this->getExcludeEmptyValues()),
        ];
    }

    /**
     * @return array
     */
    public function getExcludeEmptyValues()
    {
        $exludedKeys = $this->getParameter('exclude_empty_values');

        return is_array($exludedKeys) ? $exludedKeys : [];
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

    /**
     * @param array $exludedKeys
     *
     * @return $this
     */
    public function setExcludeEmptyValues(array $exludedKeys)
    {
        $this->setParameter('exclude_empty_values', $exludedKeys);

        return $this;
    }
}
