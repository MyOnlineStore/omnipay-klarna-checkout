<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

use Klarna\Rest\Transport\Connector;
use Klarna\Rest\Transport\ConnectorInterface;
use Omnipay\Common\AbstractGateway;

final class Gateway extends AbstractGateway
{
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

        $this->parameters->set(
            'connector',
            Connector::create(
                $this->getParameter('merchant_id'),
                $this->getParameter('secret'),
                $this->getTestMode() ? ConnectorInterface::EU_TEST_BASE_URL : ConnectorInterface::EU_BASE_URL
            )
        );

        return $this;
    }

    /**
     * @param string $merchantId
     */
    public function setMerchantId($merchantId)
    {
        $this->setParameter('merchant_id', $merchantId);
    }

    /**
     * @param string $secret
     */
    public function setSecret($secret)
    {
        $this->setParameter('secret', $secret);
    }
}
