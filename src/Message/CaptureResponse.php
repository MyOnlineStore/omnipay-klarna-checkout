<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Message\RequestInterface;

final class CaptureResponse extends AbstractResponse
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
}
