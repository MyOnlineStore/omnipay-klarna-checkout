<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

final class AuthorizeResponse extends AbstractResponse implements RedirectResponseInterface
{
    /** @var string|null */
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
    public function getRedirectUrl()
    {
        return $this->renderUrl;
    }

    public function isRedirect(): bool
    {
        return null !== $this->renderUrl;
    }

    public function isSuccessful(): bool
    {
        // Authorize is only successful once it has been acknowledged
        return false;
    }
}
