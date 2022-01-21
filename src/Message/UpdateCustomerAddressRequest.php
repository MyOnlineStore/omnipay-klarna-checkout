<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Http\Exception\NetworkException;
use Omnipay\Common\Http\Exception\RequestException;

final class UpdateCustomerAddressRequest extends AbstractOrderRequest
{
    /**
     * @return array
     *
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('transactionReference', 'billing_address', 'shipping_address');

        return [
            'shipping_address' => $this->getShippingAddress()->getArrayCopy(),
            'billing_address' => $this->getBillingAddress()->getArrayCopy(),
        ];
    }

    /**
     * @inheritDoc
     *
     * @throws RequestException when the HTTP client is passed a request that is invalid and cannot be sent.
     * @throws NetworkException if there is an error with the network or the remote server cannot be reached.
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(
            'PATCH',
            \sprintf('/ordermanagement/v1/orders/%s/customer-details', $this->getTransactionReference()),
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
