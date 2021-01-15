<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\RuntimeException;

trait MerchantUrlsDataTrait
{
    /**
     * @return string|null
     */
    public function getAddressUpdateUrl()
    {
        return $this->getParameter('addressUpdateUrl');
    }

    /**
     * @return string|null
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
    public function getMerchantUrls(): array
    {
        $this->validate('notifyUrl', 'returnUrl', 'termsUrl', 'validationUrl');

        $returnUrl = $this->getReturnUrl();

        $merchantUrls = [
            'checkout' => $returnUrl,
            'confirmation' => $this->getConfirmationUrl() ?? $returnUrl,
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
     * @return string|null
     */
    abstract public function getNotifyUrl();

    /**
     * @return string|null
     */
    abstract public function getReturnUrl();

    /**
     * @return string|null
     */
    public function getTermsUrl()
    {
        return $this->getParameter('termsUrl');
    }

    /**
     * @return string|null
     */
    public function getValidationUrl()
    {
        return $this->getParameter('validationUrl');
    }

    /**
     * @return string|null
     */
    public function getConfirmationUrl()
    {
        return $this->getParameter('confirmationUrl');
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setAddressUpdateUrl(string $url): self
    {
        $this->setParameter('addressUpdateUrl', $url);

        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setCancellationTermsUrl(string $url): self
    {
        $this->setParameter('cancellationTermsUrl', $url);

        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setTermsUrl(string $url): self
    {
        $this->setParameter('termsUrl', $url);

        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setValidationUrl(string $url): self
    {
        $this->setParameter('validationUrl', $url);

        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setConfirmationUrl(string $url): self
    {
        $this->setParameter('confirmationUrl', $url);

        return $this;
    }

    /**
     * @param string $args,... a variable length list of required parameters
     *
     * @throws InvalidRequestException
     */
    abstract public function validate(...$args);

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
