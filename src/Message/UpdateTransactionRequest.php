<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\Message\RequestInterface;

final class UpdateTransactionRequest extends AbstractOrderRequest
{
    use ItemDataTrait;

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $this->validate('transactionReference');

        return $this->getOrderData();
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        $responseData = $this->getResponseBody(
            $this->sendRequest(
                RequestInterface::POST,
                sprintf('/checkout/v3/orders/%s', $this->getTransactionReference()),
                $data
            )
        );

        // Once the checkout order has reached it's end-state it cannot be changed; update the management order instead
        if (isset($responseData['error_code']) && 'READ_ONLY_ORDER' === $responseData['error_code']) {
            $dataKeysByManagementEndpoint = [
                'customer-details' =>  ['shipping_address', 'billing_address'],
                'merchant-references' => ['merchant_reference1', 'merchant_reference2'],
            ];

            foreach ($dataKeysByManagementEndpoint as $endpoint => $dataKeys) {
                // Extract the data relevant to the current management endpoint
                $requestData = array_intersect_key($data, array_flip($dataKeys));

                // Don't perform an unnessasary request if there is no data to be updated for this management endpoint
                if (empty($requestData)) {
                    continue;
                }

                // Send the relevant data to the management endpoint
                $responseData = $this->getResponseBody(
                    $this->sendRequest(
                        RequestInterface::PATCH,
                        sprintf('/ordermanagement/v1/orders/%s/%s', $this->getTransactionReference(), $endpoint),
                        $requestData
                    )
                );

                // Stop following updates if any error occurs
                if (isset($responseData['error_code'])) {
                    break;
                }
            }
        }

        return new UpdateTransactionResponse($this, $responseData);
    }
}
