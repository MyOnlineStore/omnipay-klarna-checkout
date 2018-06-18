<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

use Money\Currencies\ISOCurrencies;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

trait CurrencyAwareTrait
{
    /**
     * @param Money $money
     *
     * @return int
     */
    protected function toCurrencyMinorUnits(Money $money): int
    {
        $moneyParser = new DecimalMoneyParser(new ISOCurrencies());

        return (int) $moneyParser->parse($money->getAmount(), $money->getCurrency());
    }
}
