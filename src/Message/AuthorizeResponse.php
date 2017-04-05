<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

final class AuthorizeResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * @var string|null
     */
    private $renderUrl;

    /**
     * @inheritDoc
     */
    public function __construct(RequestInterface $request, $data, $renderUrl = null)
    {
        parent::__construct($request, $data);
        $this->renderUrl = $renderUrl;
    }

    /**
     * @inheritDoc
     */
    public function getRedirectData()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * @inheritDoc
     */
    public function getRedirectUrl()
    {
        return $this->renderUrl;
    }

    /**
     * @inheritDoc
     */
    public function isRedirect()
    {
        return null !== $this->renderUrl;
    }

    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        return 'checkout_incomplete' !== $this->data['status'];
    }
}
