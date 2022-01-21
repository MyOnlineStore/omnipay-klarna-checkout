<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Http\Exception\NetworkException;
use Omnipay\Common\Http\Exception\RequestException;

/**
 * Creates a Klarna Checkout order if it does not exist
 */
final class AuthorizeRequest extends AbstractOrderRequest
{
    use MerchantUrlsDataTrait;

    /**
     * @inheritDoc
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'amount',
            'currency',
            'items',
            'locale',
            'purchase_country',
            'tax_amount'
        );

        $data = $this->getOrderData();
        $data['merchant_urls'] = $this->getMerchantUrls();

        return $data;
    }

    /**
     * @return string|null
     */
    public function getRenderUrl()
    {
        return $this->getParameter('render_url');
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidResponseException
     * @throws RequestException when the HTTP client is passed a request that is invalid and cannot be sent.
     * @throws NetworkException if there is an error with the network or the remote server cannot be reached.
     */
    public function sendData($data)
    {
        $response = $this->getTransactionReference() ?
            $this->sendRequest('GET', '/checkout/v3/orders/' . $this->getTransactionReference(), $data) :
            $this->sendRequest('POST', '/checkout/v3/orders', $data);

        if ($response->getStatusCode() >= 400) {
            throw new InvalidResponseException(
                \sprintf('Reason: %s (%s)', $response->getReasonPhrase(), $response->getBody())
            );
        }

        return new AuthorizeResponse($this, $this->getResponseBody($response), $this->getRenderUrl());
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setRenderUrl(string $url): self
    {
        $this->setParameter('render_url', $url);

        return $this;
    }
}
