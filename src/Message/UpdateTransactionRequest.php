<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Exception\InvalidRequestException;

final class UpdateTransactionRequest extends AbstractOrderRequest
{
    use ItemDataTrait;
    use MerchantUrlsDataTrait;

    /**
     * @inheritdoc
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
     */
    public function sendData($data)
    {
        $responseData = $this->getResponseBody(
            $this->sendRequest(
                'POST',
                sprintf('/checkout/v3/orders/%s', $this->getTransactionReference()),
                $data
            )
        );

        // Once the checkout order has reached it's end-state it cannot be changed; update the management order instead
        if (isset($responseData['error_code']) && 'READ_ONLY_ORDER' === $responseData['error_code']) {
            // Attempt to update the merchant references at the order management endpoint
            $requestData = array_intersect_key($data, ['merchant_reference1' => true, 'merchant_reference2' => true]);

            $responseData = !empty($requestData) ?
                $this->getResponseBody(
                    $this->sendRequest(
                        'PATCH',
                        sprintf('/ordermanagement/v1/orders/%s/merchant-references', $this->getTransactionReference()),
                        $requestData
                    )
                ) :
                []; // no merchant references to be updated
        }

        return new UpdateTransactionResponse($this, $responseData);
    }
}
