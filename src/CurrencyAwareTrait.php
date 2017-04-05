<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

use Omnipay\Common\Currency;

trait CurrencyAwareTrait
{
    /**
     * @return int
     */
    protected function getCurrencyMinorUnitCount()
    {
        if ($currency = Currency::find($this->getCurrency())) {
            return $currency->getDecimals();
        }

        return 2;
    }

    /**
     * @param float|int $amount
     *
     * @return int
     */
    protected function toCurrencyMinorUnits($amount)
    {
        return (int) round($amount * pow(10, $this->getCurrencyMinorUnitCount()));
    }

    /**
     * @return string
     */
    abstract public function getCurrency();
}
