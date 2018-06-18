<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Exception\InvalidRequestException;

final class AcknowledgeRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionReference');

        return null;
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(
            'POST',
            sprintf(
                '/ordermanagement/v1/orders/%s/acknowledge',
                $this->getTransactionReference()
            ),
            $data
        );

        return new AcknowledgeResponse(
            $this,
            $this->getResponseBody($response),
            $response->getStatusCode()
        );
    }
}
