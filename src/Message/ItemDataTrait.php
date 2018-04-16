<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\CurrencyAwareTrait;
use MyOnlineStore\Omnipay\KlarnaCheckout\ItemBag;

trait ItemDataTrait
{
    use CurrencyAwareTrait;

    /**
     * @param ItemBag $items
     *
     * @return array[]
     */
    public function getItemData(ItemBag $items)
    {
        $orderLines = [];

        foreach ($items as $item) {
            $orderLines[] = [
                'type' => $item->getType(),
                'name' => $item->getName(),
                'quantity' => $item->getQuantity(),
                'tax_rate' => (int) ($item->getTaxRate() * 100),
                'total_amount' => $this->toCurrencyMinorUnits($item->getQuantity() * $item->getPrice()),
                'total_tax_amount' => $this->toCurrencyMinorUnits($item->getTotalTaxAmount()),
                'unit_price' => $this->toCurrencyMinorUnits($item->getPrice()),
                'merchant_data' => $item->getMerchantData(),
            ];
        }

        return $orderLines;
    }
}
