<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\RuntimeException;

trait MerchantUrlsDataTrait
{
    /**
     * @return string
     */
    public function getAddressUpdateUrl()
    {
        return $this->getParameter('addressUpdateUrl');
    }

    /**
     * @return string
     */
    public function getCancellationTermsUrl()
    {
        return $this->getParameter('cancellationTermsUrl');
    }

    /**
     * @return array
     *
     * @throws InvalidRequestException
     */
    public function getMerchantUrls()
    {
        $this->validate('notifyUrl', 'returnUrl', 'termsUrl', 'validationUrl');

        $merchantUrls = [
            'checkout' => $this->getReturnUrl(),
            'confirmation' => $this->getReturnUrl(),
            'push' => $this->getNotifyUrl(),
            'terms' => $this->getTermsUrl(),
            'validation' => $this->getValidationUrl(),
        ];

        if (null !== ($cancellationTermsUrl = $this->getCancellationTermsUrl())) {
            $merchantUrls['cancellation_terms'] = $cancellationTermsUrl;
        }

        if (null !== ($addressUpdateUrl = $this->getAddressUpdateUrl())) {
            $merchantUrls['address_update'] = $addressUpdateUrl;
        }

        return $merchantUrls;
    }

    /**
     * @return string
     */
    abstract public function getNotifyUrl();

    /**
     * @return string
     */
    abstract public function getReturnUrl();

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
     * @param string $url
     *
     * @return $this
     */
    public function setAddressUpdateUrl($url)
    {
        $this->setParameter('addressUpdateUrl', $url);

        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setCancellationTermsUrl($url)
    {
        $this->setParameter('cancellationTermsUrl', $url);

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
    /**
     * @param string ... a variable length list of required parameters
     *
     * @throws InvalidRequestException
     */
    abstract public function validate();

    /**
     * @param string $key
     *
     * @return mixed
     */
    abstract protected function getParameter($key);

    /**
     * Set a single parameter
     *
     * @param string $key   The parameter key
     * @param mixed  $value The value to set
     *
     * @return AbstractRequest Provides a fluent interface
     *
     * @throws RuntimeException if a request parameter is modified after the request has been sent.
     */
    abstract protected function setParameter($key, $value);
}
