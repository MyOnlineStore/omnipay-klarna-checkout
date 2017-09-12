<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

use Omnipay\Common\Message\RequestInterface;

interface GatewayInterface extends \Omnipay\Common\GatewayInterface
{
    /**
     * @param array $options
     *
     * @return RequestInterface
     */
    public function acknowledge(array $options = []);

    /**
     * @param array $options
     *
     * @return RequestInterface
     */
    public function fetchTransaction(array $options = []);

    /**
     * @param array $options
     *
     * @return RequestInterface
     */
    public function updateTransaction(array $options = []);
}
