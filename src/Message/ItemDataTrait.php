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
            $totalTaxAmount = null === $item->getTotalTaxAmount()
                ? $this->convertToMoney(0)
                : $this->convertToMoney($item->getTotalTaxAmount());
            $price = null === $item->getPrice() ? $this->convertToMoney(0) : $this->convertToMoney($item->getPrice());

            $orderLines[] = [
                'type' => $item->getType(),
                'name' => $item->getName(),
                'quantity' => $item->getQuantity(),
                'tax_rate' => null === $taxRate ? 0 : (int) ($item->getTaxRate() * 100),
                'total_amount' => (int) $price->multiply($item->getQuantity())->getAmount(),
                'total_tax_amount' => (int) $totalTaxAmount->getAmount(),
                'unit_price' => (int) $price->getAmount(),
                'merchant_data' => $item->getMerchantData(),
            ];
        }

        return $orderLines;
    }

    abstract protected function convertToMoney($amount): Money;
}
