<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Creates a Klarna Checkout order if it does not exist
 */
final class AuthorizeRequest extends AbstractOrderRequest
{
    use MerchantUrlsDataTrait;

    /**
     * @inheritDoc
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
     * @return string
     */
    public function getRenderUrl()
    {
        return $this->getParameter('render_url');
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        $response = $this->getTransactionReference() ?
            $this->sendRequest(RequestInterface::GET, '/checkout/v3/orders/'.$this->getTransactionReference(), $data) :
            $this->sendRequest(RequestInterface::POST, '/checkout/v3/orders', $data);

        if ($response->getStatusCode() >= 400) {
            throw new InvalidResponseException($response->getMessage());
        }

        return new AuthorizeResponse($this, $this->getResponseBody($response), $this->getRenderUrl());
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setRenderUrl($url)
    {
        $this->setParameter('render_url', $url);

        return $this;
    }
}
