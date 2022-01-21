<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Http\Exception\NetworkException;
use Omnipay\Common\Http\Exception\RequestException;

final class CaptureRequest extends AbstractRequest
{
    use ItemDataTrait;

    /**
     * {@inheritDoc}
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionReference', 'amount');

        $data = ['captured_amount' => $this->getAmountInteger()];

        if (null !== $items = $this->getItems()) {
            $data['order_lines'] = $this->getItemData($items);
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
        $response = $this->sendRequest(
            'POST',
            \sprintf(
                '/ordermanagement/v1/orders/%s/captures',
                $this->getTransactionReference()
            ),
            $data
        );

        return new CaptureResponse(
            $this,
            $this->getResponseBody($response),
            $this->getTransactionReference(),
            $response->getStatusCode()
        );
    }
}
