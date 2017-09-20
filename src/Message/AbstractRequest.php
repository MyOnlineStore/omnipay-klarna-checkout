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
     * @return string REGION_* constant value
     */
    public function getApiRegion()
    {
        return $this->getParameter('api_region');
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->getParameter('base_url');
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
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchant_id');
    }

    /**
     * @return string|null
     */
    public function getMerchantReference1()
    {
        return $this->getParameter('merchant_reference1');
    }

    /**
     * @return string|null
     */
    public function getMerchantReference2()
    {
        return $this->getParameter('merchant_reference2');
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->getParameter('secret');
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
     * @param string $merchantReference
     *
     * @return $this
     */
    public function setMerchantReference1($merchantReference)
    {
        $this->setParameter('merchant_reference1', $merchantReference);

        return $this;
    }

    /**
     * @param string $merchantReference
     *
     * @return $this
     */
    public function setMerchantReference2($merchantReference)
    {
        $this->setParameter('merchant_reference2', $merchantReference);

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
     * @param int $value
     */
    public function setTaxAmount($value)
    {
        $this->setParameter('tax_amount', $value);
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
        return empty($response->getBody(true)) ? [] : $response->json();
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
}
