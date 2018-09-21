<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Exception\ParserException;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

trait CurrencyAwareTrait
{
    /**
     * @param mixed $amount
     *
     * @return Money
     *
     * @throws ParserException
     */
    protected function convertToMoney($amount): Money
    {
        if ($amount instanceof Money) {
            return $amount;
        }

        try {
            $currency = new Currency($this->getCurrency());
        } catch (\InvalidArgumentException $exception) {
            $currency = new Currency('USD');
        }

        $moneyParser = new DecimalMoneyParser(new ISOCurrencies());

        return $moneyParser->parse((string) $amount, $currency);
    }

    /**
     * @param Money $money
     *
     * @return int
     *
     * @throws ParserException
     */
    protected function toCurrencyMinorUnits(Money $money): int
    {
        $moneyParser = new DecimalMoneyParser(new ISOCurrencies());

        return (int) $moneyParser->parse($money->getAmount(), $money->getCurrency())->getAmount();
    }
}
