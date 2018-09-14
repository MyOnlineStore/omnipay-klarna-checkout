<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Money\Money;
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
            $taxRate = $item->getTaxRate();
            $totalTaxAmount = $item->getTotalTaxAmount();
            $price = $item->getPrice();

            $orderLines[] = [
                'type' => $item->getType(),
                'name' => $item->getName(),
                'quantity' => $item->getQuantity(),
                'tax_rate' => null === $taxRate ? 0 : (int) ($item->getTaxRate() * 100),
                'total_amount' => null === $price ? 0 : $item->getQuantity() * $price,
                'total_tax_amount' => null === $totalTaxAmount ? 0 : (int) $this->convertToMoney($totalTaxAmount)
                    ->getAmount(),
                'unit_price' => null === $price ? 0 : (int) $price,
                'merchant_data' => $item->getMerchantData(),
            ];
        }

        return $orderLines;
    }

    abstract protected function convertToMoney($amount): Money;
}
