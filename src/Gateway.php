<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

use Klarna\Rest\Transport\Connector;
use Klarna\Rest\Transport\ConnectorInterface;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AuthorizeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\CaptureRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\RefundRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\VoidRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;

final class Gateway extends AbstractGateway
{
    const API_VERSION_EUROPE = 'EU';
    const API_VERSION_NORTH_AMERICA = 'NA';

    /**
     * @param array $options
     *
     * @return RequestInterface
     */
    public function authorize(array $options = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }

    /**
     * @param array $options
     *
     * @return RequestInterface
     */
    public function capture(array $options = [])
    {
        return $this->createRequest(CaptureRequest::class, $options);
    }

    /**
     * @param  array $options
     *
     * @return RequestInterface
     */
    public function fetchTransaction(array $options = array())
    {
        return $this->createRequest(FetchTransactionRequest::class, $options);
    }

    /**
     * @param array $options
     *
     * @return RequestInterface
     */
    public function refund(array $options = [])
    {
        return $this->createRequest(RefundRequest::class, $options);
    }

    /**
     * @param array $options
     *
     * @return RequestInterface
     */
    public function void(array $options = [])
    {
        return $this->createRequest(VoidRequest::class, $options);
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
        ];
    }

    /**
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
     * @inheritDoc
     */
    public function initialize(array $parameters = array())
    {
        parent::initialize($parameters);

        if (self::API_VERSION_EUROPE === $this->getApiRegion()) {
            $baseUrl = $this->getTestMode() ? ConnectorInterface::EU_TEST_BASE_URL : ConnectorInterface::EU_BASE_URL;
        } else {
            $baseUrl = $this->getTestMode() ? ConnectorInterface::NA_TEST_BASE_URL : ConnectorInterface::NA_BASE_URL;
        }

        $this->parameters->set(
            'connector',
            Connector::create($this->getMerchantId(), $this->getSecret(), $baseUrl)
        );

        return $this;
    }

    /**
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
     * @param string $secret
     *
     * @return $this
     */
    public function setSecret($secret)
    {
        $this->setParameter('secret', $secret);

        return $this;
    }
}
