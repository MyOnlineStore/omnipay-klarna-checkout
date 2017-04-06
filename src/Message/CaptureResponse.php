<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\ResponseInterface;

final class CaptureResponse extends AbstractResponse implements ResponseInterface
{
    /**
     * @var string
     */
    private $transactionReference;

    /**
     * @param RequestInterface $request
     * @param mixed            $data
     * @param string           $transactionReference
     */
    public function __construct(RequestInterface $request, $data, $transactionReference)
    {
        parent::__construct($request, $data);

        $this->transactionReference = $transactionReference;
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
        return !isset($this->data['error_code']);
    }
}
