<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Http\Exception\NetworkException;
use Omnipay\Common\Http\Exception\RequestException;

final class VoidRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionReference');

        return [];
    }

    /**
     * @inheritdoc
     *
     * @throws RequestException when the HTTP client is passed a request that is invalid and cannot be sent.
     * @throws NetworkException if there is an error with the network or the remote server cannot be reached.
     */
    public function sendData($data)
    {
        $baseUrl = \sprintf('/ordermanagement/v1/orders/%s', $this->getTransactionReference());
        $orderManagementResponse = $this->sendRequest('GET', $baseUrl, []);

        $order = $this->getResponseBody($orderManagementResponse);

        $voidUrl = \sprintf('%s/release-remaining-authorization', $baseUrl);

        if (empty($order['captures'])) {
            $voidUrl = \sprintf('%s/cancel', $baseUrl);
        }

        $response = $this->sendRequest('POST', $voidUrl, $data);

        return new VoidResponse(
            $this,
            $this->getResponseBody($response),
            $response->getStatusCode()
        );
    }
}
