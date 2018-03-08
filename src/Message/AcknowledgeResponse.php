<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Message\RequestInterface;

final class AcknowledgeResponse extends AbstractResponse
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @param RequestInterface $request
     * @param mixed            $data
     * @param int              $statusCode
     */
    public function __construct(RequestInterface $request, $data, $statusCode)
    {
        parent::__construct($request, $data);

        $this->statusCode = $statusCode;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return parent::isSuccessful() && 204 === $this->getStatusCode();
    }
}
