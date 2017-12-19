<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Guzzle\Http\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Creates a Klarna Checkout order if it does not exist
 */
final class AuthorizeRequest extends AbstractOrderRequest
{
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
            'notifyUrl',
            'returnUrl',
            'tax_amount',
            'termsUrl',
            'validationUrl'
        );

        $data = $this->getOrderData();
        $data['merchant_urls'] = [
            'checkout' => $this->getReturnUrl(),
            'confirmation' => $this->getReturnUrl(),
            'push' => $this->getNotifyUrl(),
            'terms' => $this->getTermsUrl(),
            'validation' => $this->getValidationUrl(),
        ];

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
     * @return string
     */
    public function getTermsUrl()
    {
        return $this->getParameter('termsUrl');
    }

    /**
     * @return string
     */
    public function getValidationUrl()
    {
        return $this->getParameter('validationUrl');
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

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setTermsUrl($url)
    {
        $this->setParameter('termsUrl', $url);

        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setValidationUrl($url)
    {
        $this->setParameter('validationUrl', $url);

        return $this;
    }
}
