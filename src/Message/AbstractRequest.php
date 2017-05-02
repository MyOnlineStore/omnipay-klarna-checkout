<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Guzzle\Common\Event;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use MyOnlineStore\Omnipay\KlarnaCheckout\CurrencyAwareTrait;
use MyOnlineStore\Omnipay\KlarnaCheckout\ItemBag;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * @method ItemBag|null getItems()
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    use CurrencyAwareTrait;

    /**
     * @inheritdoc
     */
    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest)
    {
        parent::__construct($httpClient, $httpRequest);

        // don't throw exceptions for 4xx errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function (Event $event) {
                if ($event['response']->isClientError()) {
                    $event->stopPropagation();
                }
            }
        );
    }

    /**
     * RFC 1766 customer's locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->getParameter('locale');
    }

    /**
     * The total tax amount of the order
     *
     * @return int
     */
    public function getTaxAmount()
    {
        return $this->getParameter('tax_amount');
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
    public function getMerchantId()
    {
        return $this->getParameter('merchant_id');
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
    public function getBaseUrl()
    {
        return $this->getParameter('base_url');
    }

    /**
     * @inheritdoc
     */
    public function setItems($items)
    {
        if ($items && !$items instanceof ItemBag) {
            $items = new ItemBag($items);
        }

        return $this->setParameter('items', $items);
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->setParameter('locale', $locale);
    }

    /**
     * @param int $value
     */
    public function setTaxAmount($value)
    {
        $this->setParameter('tax_amount', $value);
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

    /**
     * @param string $baseUrl
     *
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->setParameter('base_url', $baseUrl);

        return $this;
    }

    /**
     * @param string $method
     * @param string $url
     * @param mixed  $data
     *
     * @return Response
     */
    protected function sendRequest($method, $url, $data)
    {
        if (RequestInterface::GET === $method) {
            return $this->httpClient->createRequest(
                $method,
                $this->getBaseUrl().$url,
                null,
                null,
                $this->getRequestOptions()
            )->send();
        }

        return $this->httpClient->createRequest(
            $method,
            $this->getBaseUrl().$url,
            ['Content-Type' => 'application/json'],
            json_encode($data),
            $this->getRequestOptions()
        )->send();
    }

    /**
     * @return array
     */
    protected function getRequestOptions()
    {
        return ['auth' => [$this->getMerchantId(), $this->getSecret()]];
    }

    /**
     * @param Response $response
     *
     * @return array
     */
    protected function getResponseBody(Response $response)
    {
        return $response->json();
    }
}
