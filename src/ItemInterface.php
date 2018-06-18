<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

interface ItemInterface extends \Omnipay\Common\ItemInterface
{
    /**
     * @return string
     */
    public function getMerchantData(): string;

    /**
     * Non-negative percentage (i.e. 25 = 25%)
     *
     * @return float
     */
    public function getTaxRate();

    /**
     * Total amount of tax
     *
     * @return float
     */
    public function getTotalTaxAmount();

    /**
     * Product type
     *
     * @return string
     */
    public function getType(): string;
}
