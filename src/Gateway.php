<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AcknowledgeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AuthorizeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\CaptureRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\RefundRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\UpdateTransactionRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\VoidRequest;
use Omnipay\Common\AbstractGateway;

final class Gateway extends AbstractGateway implements GatewayInterface
{
    const API_VERSION_EUROPE = 'EU';
    const API_VERSION_NORTH_AMERICA = 'NA';

    const EU_BASE_URL = 'https://api.klarna.com';
    const EU_TEST_BASE_URL = 'https://api.playground.klarna.com';
    const NA_BASE_URL = 'https://api-na.klarna.com';
    const NA_TEST_BASE_URL = 'https://api-na.playground.klarna.com';

    /**
     * @inheritdoc
     */
    public function acknowledge(array $options = [])
    {
        return $this->createRequest(AcknowledgeRequest::class, $options);
    }

    /**
     * @inheritdoc
     */
    public function authorize(array $options = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }

    /**
     * @inheritdoc
     */
    public function capture(array $options = [])
    {
        return $this->createRequest(CaptureRequest::class, $options);
    }

    /**
     * @inheritdoc
     */
    public function fetchTransaction(array $options = [])
    {
        return $this->createRequest(FetchTransactionRequest::class, $options);
    }

    /**
     * @return string REGION_* constant value
     */
    public function getApiRegion()
    {
        return $this->getParameter('api_region');
    }

    /**
     * @inheritDoc
     */
    public function getDefaultParameters()
    {
        return [
            'api_region' => self::API_VERSION_EUROPE,
            'merchant_id' => '',
            'secret' => '',
            'testMode' => true,
            'username' => '',
        ];
    }

    /**
     * @deprecated use getUsername instead
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchant_id');
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'KlarnaCheckout';
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * @inheritDoc
     */
    public function initialize(array $parameters = [])
    {
        parent::initialize($parameters);

        if (self::API_VERSION_EUROPE === $this->getApiRegion()) {
            $this->parameters->set('base_url', $this->getTestMode() ? self::EU_TEST_BASE_URL : self::EU_BASE_URL);
        } else {
            $this->parameters->set('base_url', $this->getTestMode() ? self::NA_TEST_BASE_URL : self::NA_BASE_URL);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function refund(array $options = [])
    {
        return $this->createRequest(RefundRequest::class, $options);
    }

    /**
     * @param string $region
     *
     * @return $this
     */
    public function setApiRegion($region)
    {
        $this->setParameter('api_region', $region);

        return $this;
    }

    /**
     * @deprecated use setUsername instead
     *
     * @param string $merchantId
     *
     * @return $this
     */
    public function setMerchantId($merchantId)
    {
        $this->setParameter('merchant_id', $merchantId);

        return $this;
    }

    /**
     * @param string $secret
     *
     * @return $this
     */
    public function setSecret($secret)
    {
        $this->setParameter('secret', $secret);

        return $this;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->setParameter('username', $username);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function updateTransaction(array $options = [])
    {
        return $this->createRequest(UpdateTransactionRequest::class, $options);
    }

    /**
     * @inheritdoc
     */
    public function void(array $options = [])
    {
        return $this->createRequest(VoidRequest::class, $options);
    }
}
