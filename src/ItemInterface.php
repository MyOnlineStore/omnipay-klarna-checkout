<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

interface ItemInterface extends \Omnipay\Common\ItemInterface
{
    /**
     * @return string|null
     */
    public function getMerchantData();

    /**
     * Non-negative percentage (i.e. 25 = 25%)
     *
     * @return float|null
     */
    public function getTaxRate();

    /**
     * Total amount
     *
     * @return float|null
     */
    public function getTotalAmount();

    /**
     * Total amount of discount
     *
     * @return float|null
     */
    public function getTotalDiscountAmount();

    /**
     * Total amount of tax
     *
     * @return float|null
     */
    public function getTotalTaxAmount();

    /**
     * Product type
     *
     * @return string|null
     */
    public function getType();
}
