<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

use Omnipay\Common\Message\RequestInterface;

interface GatewayInterface extends \Omnipay\Common\GatewayInterface
{
    /**
     * @param array $options
     *
     * @return RequestInterface
     */
    public function acknowledge(array $options = []): RequestInterface;

    /**
     * @param array $options
     *
     * @return RequestInterface
     */
    public function extendAuthorization(array $options = []): RequestInterface;

    /**
     * @param array $options
     *
     * @return RequestInterface
     */
    public function fetchTransaction(array $options = []): RequestInterface;

    /**
     * @param array $options
     *
     * @return RequestInterface
     */
    public function updateTransaction(array $options = []): RequestInterface;

    /**
     * @param array $options
     *
     * @return RequestInterface
     */
    public function updateMerchantReferences(array $options = []): RequestInterface;

    /**
     * @param array $options
     */
    public function updateCustomerAddress(array $options = []): RequestInterface;
}
