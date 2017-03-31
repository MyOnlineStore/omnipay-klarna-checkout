<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\ResponseInterface;

final class CaptureResponse extends AbstractResponse implements ResponseInterface
{
    /**
     * @var mixed
     */
    private $orderData;

    /**
     * @param RequestInterface $request
     * @param mixed            $captureData
     * @param mixed            $orderData
     */
    public function __construct(RequestInterface $request, $captureData, $orderData)
    {
        parent::__construct($request, $captureData);

        $this->orderData = $orderData;
    }

    /**
     * @inheritDoc
     */
    public function getTransactionReference()
    {
        return $this->orderData['order_id'];
    }

    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return !isset($this->data['error_code']);
    }
}
