<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\ItemBag;

trait ItemDataTrait
{
    /**
     * @param ItemBag|null $items
     *
     * @return array
     */
    public function getItemData(ItemBag $items = null)
    {
        $orderLines = [];

        if (null === $items) {
            return $orderLines;
        }

        foreach ($items as $item) {
            $orderLines[] = [
                'name' => $item->getName(),
                'quantity' => $item->getQuantity(),
                'tax_rate' => (int) $item->getTaxRate() * 100,
                'total_amount' => $item->getQuantity() * $item->getPrice() * 100,
                'total_tax_amount' => $item->getTotalTaxAmount() * 100,
                'unit_price' => $item->getPrice() * 100,
            ];
        }

        return $orderLines;
    }
}
