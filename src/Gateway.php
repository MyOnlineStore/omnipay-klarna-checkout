<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

use Klarna\Rest\Transport\Connector;
use Klarna\Rest\Transport\ConnectorInterface;
use Omnipay\Common\AbstractGateway;

final class Gateway extends AbstractGateway
{
    const API_VERSION_EUROPE = 'EU';
    const API_VERSION_NORTH_AMERICA = 'NA';

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'KlarnaCheckout';
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
     * @inheritDoc
     */
    public function initialize(array $parameters = array())
    {
        parent::initialize($parameters);

        if (self::API_VERSION_EUROPE === $this->getApiRegion()) {
            $baseUrl = $this->getTestMode() ? ConnectorInterface::EU_TEST_BASE_URL : ConnectorInterface::EU_BASE_URL;
        } else {
            $baseUrl = $this->getTestMode() ? ConnectorInterface::NA_BASE_URL : ConnectorInterface::NA_TEST_BASE_URL;
        }

        $this->parameters->set(
            'connector',
            Connector::create($this->getMerchantId(), $this->getSecret(), $baseUrl)
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchant_id');
    }

    /**
     * @return string REGION_* constant value
     */
    public function getApiRegion()
    {
        return $this->getParameter('api_region');
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->getParameter('secret');
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
