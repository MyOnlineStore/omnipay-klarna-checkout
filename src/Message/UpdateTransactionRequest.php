<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Http\Exception\NetworkException;
use Omnipay\Common\Http\Exception\RequestException;

final class UpdateTransactionRequest extends AbstractOrderRequest
{
    use ItemDataTrait;
    use MerchantUrlsDataTrait;

    /**
     * @inheritdoc
     *
     * @throws InvalidRequestException
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionReference');
        $data = $this->getOrderData();

        try {
            $data['merchant_urls'] = $this->getMerchantUrls();
        } catch (InvalidRequestException $exception) {
            // Insufficient data for merchant urls
        }

        return $data;
    }

    /**
     * @inheritdoc
     *
     * @throws RequestException when the HTTP client is passed a request that is invalid and cannot be sent.
     * @throws NetworkException if there is an error with the network or the remote server cannot be reached.
     */
    public function sendData($data)
    {
        return new UpdateTransactionResponse(
            $this,
            $this->getResponseBody(
                $this->sendRequest(
                    'POST',
                    \sprintf('/checkout/v3/orders/%s', $this->getTransactionReference()),
                    $data
                )
            )
        );
    }
}
