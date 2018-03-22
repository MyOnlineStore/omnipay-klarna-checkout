<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Message\RequestInterface;

final class CaptureResponse extends AbstractResponse
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $transactionReference;

    /**
     * @param RequestInterface $request
     * @param mixed            $data
     * @param string           $transactionReference
     * @param int              $statusCode
     */
    public function __construct(RequestInterface $request, $data, $transactionReference, $statusCode)
    {
        parent::__construct($request, $data);

        $this->transactionReference = $transactionReference;
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
    public function getTransactionReference()
    {
        return $this->transactionReference;
    }

    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return parent::isSuccessful() && 201 === $this->statusCode;
    }
}
