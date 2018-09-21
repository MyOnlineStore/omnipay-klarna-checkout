<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use Money\Currency;
use Money\Money;
use MyOnlineStore\Omnipay\KlarnaCheckout\ItemBag;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AbstractRequest;
use Omnipay\Tests\TestCase;

final class AbstractRequestTest extends TestCase
{
    /**
     * @var AbstractRequest
     */
    private $request;

    protected function setUp()
    {
        $httpClient = $this->getHttpClient();
        $httpRequest = $this->getHttpRequest();

        $this->request = new class($httpClient, $httpRequest) extends AbstractRequest
        {
            /**
             * @inheritdoc
             */
            public function sendData($data)
            {
                return parent::sendData($data);
            }

            /**
             * @inheritdoc
             */
            public function getData()
            {
                return parent::getData();
            }
        };
    }

    public function testGetTaxAmountWithDecimalStringShouldReturnCorrectValue()
    {
        $taxAmount = '5.25';
        $currencyIso = 'EUR';

        $this->request->setCurrency($currencyIso);
        $this->request->setTaxAmount($taxAmount);

        self::assertSame('525', $this->request->getTaxAmount()->getAmount());
    }

    public function testGetAmountWithDecimalStringShouldReturnCorrectValue()
    {
        $amount = '5.25';
        $currencyIso = 'EUR';

        $this->request->setCurrency($currencyIso);
        $this->request->setAmount($amount);

        self::assertSame('525', $this->request->getAmount()->getAmount());
    }

    public function testGetters()
    {
        $locale = 'nl_NL';
        $taxAmount = 500;
        $currencyIso = 'EUR';

        $this->request->setCurrency($currencyIso);
        $this->request->setLocale($locale);
        $this->request->setTaxAmount(new Money($taxAmount, new Currency($currencyIso)));

        self::assertSame($locale, $this->request->getLocale());
        self::assertSame((string) $taxAmount, $this->request->getTaxAmount()->getAmount());
    }

    public function testSetItemsWithArray()
    {
        $itemsArray = [['tax_rate' => 10]];

        $this->request->setItems($itemsArray);

        self::assertEquals(new ItemBag($itemsArray), $this->request->getParameters()['items']);
    }

    public function testSetItemsWithItemBag()
    {
        $itemBag = $this->createMock(ItemBag::class);

        $this->request->setItems($itemBag);

        self::assertSame($itemBag, $this->request->getParameters()['items']);
    }
}
