<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Money\Money;
use MyOnlineStore\Omnipay\KlarnaCheckout\AuthenticationRequestHeaderProvider;
use MyOnlineStore\Omnipay\KlarnaCheckout\CurrencyAwareTrait;
use MyOnlineStore\Omnipay\KlarnaCheckout\ItemBag;
use Omnipay\Common\Http\Exception\NetworkException;
use Omnipay\Common\Http\Exception\RequestException;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Psr\Http\Message\ResponseInterface;

/**
 * @method ItemBag|null getItems()
 */
abstract class AbstractRequest extends BaseAbstractRequest
{
    use CurrencyAwareTrait;

    /**
     * @return Money|null
     */
    public function getAmount()
    {
        if (null === $amount = $this->getParameter('amount')) {
            return null;
        }

        return $this->convertToMoney($amount);
    }

    /**
     * @return string|null REGION_* constant value
     */
    public function getApiRegion()
    {
        return $this->getParameter('api_region');
    }

    /**
     * @return string|null
     */
    public function getBaseUrl()
    {
        return $this->getParameter('base_url');
    }

    /**
     * RFC 1766 customer's locale.
     *
     * @return string|null
     */
    public function getLocale()
    {
        return $this->getParameter('locale');
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
     * @return string|null
     */
    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    /**
     * The total tax amount of the order
     *
     * @return Money|null
     */
    public function getTaxAmount()
    {
        if (null === $amount = $this->getParameter('tax_amount')) {
            return null;
        }

        return $this->convertToMoney($amount);
    }

    /**
     * @return string|null
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * @param string $region
     *
     * @return $this
     */
    public function setApiRegion(string $region): self
    {
        $this->setParameter('api_region', $region);

        return $this;
    }

    /**
     * @param string $baseUrl
     *
     * @return $this
     */
    public function setBaseUrl(string $baseUrl): self
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

    public function setLocale(string $locale)
    {
        $this->setParameter('locale', $locale);
    }

    /**
     * @param string $merchantReference
     *
     * @return $this
     */
    public function setMerchantReference1(string $merchantReference): self
    {
        $this->setParameter('merchant_reference1', $merchantReference);

        return $this;
    }

    /**
     * @param string $merchantReference
     *
     * @return $this
     */
    public function setMerchantReference2(string $merchantReference): self
    {
        $this->setParameter('merchant_reference2', $merchantReference);

        return $this;
    }

    /**
     * @param string $secret
     *
     * @return $this
     */
    public function setSecret(string $secret): self
    {
        $this->setParameter('secret', $secret);

        return $this;
    }

    /**
     * @param mixed $value
     */
    public function setTaxAmount($value)
    {
        $this->setParameter('tax_amount', $value);
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->setParameter('username', $username);

        return $this;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array
     */
    protected function getResponseBody(ResponseInterface $response): array
    {
        try {
            return \json_decode($response->getBody()->getContents(), true);
        } catch (\TypeError $exception) {
            return [];
        }
    }

    /**
     * @param string $method
     * @param string $url
     * @param mixed  $data
     *
     * @return ResponseInterface
     *
     * @throws RequestException when the HTTP client is passed a request that is invalid and cannot be sent.
     * @throws NetworkException if there is an error with the network or the remote server cannot be reached.
     */
    protected function sendRequest(string $method, string $url, $data): ResponseInterface
    {
        $headers = (new AuthenticationRequestHeaderProvider())->getHeaders($this);

        if ('GET' === $method) {
            return $this->httpClient->request(
                $method,
                $this->getBaseUrl() . $url,
                $headers
            );
        }

        return $this->httpClient->request(
            $method,
            $this->getBaseUrl() . $url,
            \array_merge(
                ['Content-Type' => 'application/json'],
                $headers
            ),
            \json_encode($data)
        );
    }
}
