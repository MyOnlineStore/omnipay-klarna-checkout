<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Message\RequestInterface;

final class UpdateCustomerAddressResponse extends AbstractResponse
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

        $this->statusCode = (int) $statusCode;
    }

    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return parent::isSuccessful() && 204 === $this->statusCode;
    }
}
