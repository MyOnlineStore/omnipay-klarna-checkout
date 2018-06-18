<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\ItemBag;

trait ItemDataTrait
{
    /**
     * @param ItemBag $items
     *
     * @return array[]
     */
    public function getItemData(ItemBag $items): array
    {
        $orderLines = [];

        foreach ($items as $item) {
            $orderLines[] = [
                'type' => $item->getType(),
                'name' => $item->getName(),
                'quantity' => $item->getQuantity(),
                'tax_rate' => (int) $item->getTaxRate(),
                'total_amount' => $item->getQuantity() * $item->getPrice(),
                'total_tax_amount' => (int) $item->getTotalTaxAmount(),
                'unit_price' => (int) $item->getPrice(),
                'merchant_data' => $item->getMerchantData(),
            ];
        }

        return $orderLines;
    }
}
