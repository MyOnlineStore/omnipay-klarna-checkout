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
            $price = null === $item->getPrice() ? $this->convertToMoney(0) : $this->convertToMoney($item->getPrice());
            $totalTaxAmount = null === $item->getTotalTaxAmount()
                ? $this->convertToMoney(0)
                : $this->convertToMoney($item->getTotalTaxAmount());
            $totalDiscountAmount = null === $item->getTotalDiscountAmount()
                ? $this->convertToMoney(0)
                : $this->convertToMoney($item->getTotalDiscountAmount());
            $totalAmount = null === $item->getTotalAmount()
                ? $price->multiply($item->getQuantity())->subtract($totalDiscountAmount)
                : $this->convertToMoney($item->getTotalAmount());

            $orderLines[] = [
                'type' => $item->getType(),
                'name' => $item->getName(),
                'quantity' => $item->getQuantity(),
                'tax_rate' => null === $taxRate ? 0 : (int) ($item->getTaxRate() * 100),
                'total_amount' => (int) $totalAmount->getAmount(),
                'total_tax_amount' => (int) $totalTaxAmount->getAmount(),
                'total_discount_amount' => (int) $totalDiscountAmount->getAmount(),
                'unit_price' => (int) $price->getAmount(),
                'merchant_data' => $item->getMerchantData(),
            ];
        }

        return $orderLines;
    }

    abstract protected function convertToMoney($amount): Money;
}
