<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use Money\Currency;
use Money\Money;
use MyOnlineStore\Omnipay\KlarnaCheckout\ItemBag;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AuthorizeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AuthorizeResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Psr\Http\Message\ResponseInterface;

class AuthorizeRequestTest extends RequestTestCase
{
    use ItemDataTestTrait;
    use MerchantUrlsDataTestTrait;

    /** @var AuthorizeRequest */
    private $authorizeRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authorizeRequest = new AuthorizeRequest($this->httpClient, $this->getHttpRequest());
    }

    /**
     * @return array
     */
    public function invalidRequestDataProvider(): array
    {
        $itemBag = $this->createMock(ItemBag::class);
        $itemBag->method('getIterator')->willReturn(new \ArrayIterator([]));

        $data = \array_merge(
            [
                'currency' => 'EUR',
                'amount' => true,
                'items' => $itemBag,
                'locale' => true,
                'purchase_country' => true,
                'tax_amount' => new Money(1, new Currency('EUR')),
            ],
            \array_fill_keys(\array_keys($this->getMinimalValidMerchantUrlData()), true)
        );

        $cases = [];

        foreach ($data as $key => $value) {
            $cases[] = [\array_diff_key($data, [$key => $value])];
        }

        return $cases;
    }

    public function testGetDataWillReturnCorrectData()
    {
        $this->authorizeRequest->initialize(
            \array_merge(
                [
                    'currency' => 'EUR',
                    'locale' => 'nl_NL',
                    'amount' => '100.00',
                    'tax_amount' => 21,
                    'purchase_country' => 'NL',
                ],
                $this->getCompleteValidMerchantUrlData()
            )
        );

        $this->authorizeRequest->setItems([$this->getItemMock()]);

        self::assertEquals(
            [
                'locale' => 'nl-NL',
                'order_amount' => 10000,
                'order_tax_amount' => 2100,
                'order_lines' => [$this->getExpectedOrderLine()],
                'merchant_urls' => $this->getCompleteExpectedMerchantUrlData(),
                'purchase_country' => 'NL',
                'purchase_currency' => 'EUR',
            ],
            $this->authorizeRequest->getData()
        );
    }

    /**
     * @dataProvider invalidRequestDataProvider
     *
     * @param array $requestData
     *
     * @throws InvalidRequestException
     */
    public function testGetDataWillThrowExceptionForInvalidRequest(array $requestData)
    {
        $this->authorizeRequest->initialize($requestData);

        $this->expectException(InvalidRequestException::class);
        $this->authorizeRequest->getData();
    }

    public function testGetDataWithAddressesWillReturnCorrectData()
    {
        $organization = 'Foo inc';
        $reference = 'ref';
        $attention = 'quz';
        $email = 'foo@bar.com';
        $title = 'Mr.';
        $streetAddress = 'Foo Street 1';
        $streetAddress2 = 'App. 12A';
        $streetName = 'Foo Street';
        $houseExtension = 'C';
        $streetNumber = '1';
        $postalCode = '523354';
        $city = 'Oss';
        $region = 'NB';
        $phone = '24234234';
        $country = 'NL';

        $shippingAddress = [
            'organization_name' => $organization,
            'reference' => $reference,
            'attention' => $attention,
            'given_name' => 'foo',
            'family_name' => 'bar',
            'email' => $email,
            'title' => $title,
            'street_address' => $streetAddress,
            'street_address2' => $streetAddress2,
            'street_name' => $streetName,
            'street_number' => $streetNumber,
            'house_extension' => $houseExtension,
            'postal_code' => $postalCode,
            'city' => $city,
            'region' => $region,
            'phone' => $phone,
            'country' => $country,
        ];
        $billingAddress = [
            'organization_name' => $organization,
            'reference' => $reference,
            'attention' => $attention,
            'given_name' => 'bar',
            'family_name' => 'foo',
            'email' => $email,
            'title' => $title,
            'street_address' => $streetAddress,
            'street_address2' => $streetAddress2,
            'street_name' => $streetName,
            'street_number' => $streetNumber,
            'house_extension' => $houseExtension,
            'postal_code' => $postalCode,
            'city' => $city,
            'region' => $region,
            'phone' => $phone,
            'country' => $country,
        ];

        $this->authorizeRequest->initialize(
            \array_merge(
                [
                    'currency' => 'EUR',
                    'locale' => 'nl_NL',
                    'amount' => '100.00',
                    'tax_amount' => 21,
                    'purchase_country' => 'DE',
                ],
                $this->getMinimalValidMerchantUrlData()
            )
        );
        $this->authorizeRequest->setItems([$this->getItemMock()]);
        $this->authorizeRequest->setBillingAddress($billingAddress);
        $this->authorizeRequest->setShippingAddress($shippingAddress);

        self::assertEquals(
            [
                'locale' => 'nl-NL',
                'order_amount' => 10000,
                'order_tax_amount' => 2100,
                'order_lines' => [$this->getExpectedOrderLine()],
                'merchant_urls' => $this->getMinimalExpectedMerchantUrlData(),
                'purchase_country' => 'DE',
                'purchase_currency' => 'EUR',
                'shipping_address' => $shippingAddress,
                'billing_address' => $billingAddress,
            ],
            $this->authorizeRequest->getData()
        );
    }

    public function testGetDataWithCustomerWillReturnCorrectData()
    {
        $customer = [
            'date_of_birth' => '1995-10-20',
            'type' => 'organization',
        ];

        $this->authorizeRequest->initialize(
            \array_merge(
                [
                    'locale' => 'nl_NL',
                    'amount' => '100.00',
                    'tax_amount' => 21,
                    'currency' => 'EUR',
                    'purchase_country' => 'FR',
                ],
                $this->getCompleteValidMerchantUrlData()
            )
        );
        $this->authorizeRequest->setCustomer($customer);
        $this->authorizeRequest->setItems([$this->getItemMock()]);

        self::assertEquals(
            [
                'locale' => 'nl-NL',
                'order_amount' => 10000,
                'order_tax_amount' => 2100,
                'order_lines' => [$this->getExpectedOrderLine()],
                'merchant_urls' => $this->getCompleteExpectedMerchantUrlData(),
                'purchase_country' => 'FR',
                'purchase_currency' => 'EUR',
                'customer' => $customer,
            ],
            $this->authorizeRequest->getData()
        );
    }

    public function testGetDataWithOptionsWillReturnCorrectData()
    {
        $widgetOptions = [
            'acquiring_channel' => 'foo',
            'allow_separate_shipping_address' => true,
            'color_button' => '#FFFFF',
            'color_button_text' => '#FFFFF',
            'color_checkbox' => '#FFFFF',
            'color_checkbox_checkmark' => '#FFFFF',
            'color_header' => '#FFFFF',
            'color_link' => '#FFFFF',
            'date_of_birth_mandatory' => true,
            'shipping_details' => 'Delivered within 1-3 working days',
            'title_mandatory' => true,
            'additional_checkbox' => [
                'text' => 'Please add me to the newsletter list',
                'checked' => false,
                'required' => false,
            ],
            'radius_border' => '5px',
            'show_subtotal_detail' => true,
            'require_validate_callback_success' => true,
            'allow_global_billing_countries' => false,
        ];

        $this->authorizeRequest->initialize(
            \array_merge(
                [
                    'locale' => 'nl_NL',
                    'amount' => '100.00',
                    'tax_amount' => 21,
                    'currency' => 'EUR',
                    'shipping_countries' => ['NL', 'DE'],
                    'purchase_country' => 'BE',
                ],
                $this->getMinimalValidMerchantUrlData()
            )
        );
        $this->authorizeRequest->setItems([$this->getItemMock()]);
        $this->authorizeRequest->setWidgetOptions($widgetOptions);

        self::assertEquals(
            [
                'locale' => 'nl-NL',
                'order_amount' => 10000,
                'order_tax_amount' => 2100,
                'order_lines' => [$this->getExpectedOrderLine()],
                'merchant_urls' => $this->getMinimalExpectedMerchantUrlData(),
                'purchase_country' => 'BE',
                'purchase_currency' => 'EUR',
                'options' => $widgetOptions,
                'shipping_countries' => ['NL', 'DE'],
            ],
            $this->authorizeRequest->getData()
        );
    }

    public function testSendDataWillCreateOrderAndReturnResponse()
    {
        $inputData = ['request-data' => 'yey?'];
        $expectedData = ['response-data' => 'yey!'];

        $response = $this->setExpectedPostRequest($inputData, $expectedData, self::BASE_URL . '/checkout/v3/orders');

        $response->expects(self::once())->method('getStatusCode')->willReturn(200);

        $this->authorizeRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'username' => self::USERNAME,
                'secret' => self::SECRET,
            ]
        );
        $this->authorizeRequest->setRenderUrl('localhost/render');

        $authorizeResponse = $this->authorizeRequest->sendData($inputData);

        self::assertInstanceOf(AuthorizeResponse::class, $authorizeResponse);
        self::assertSame($expectedData, $authorizeResponse->getData());
        self::assertEquals('localhost/render', $authorizeResponse->getRedirectUrl());
    }

    public function testSendDataWillFetchOrderAndReturnResponseIfTransactionIdAlreadySet()
    {
        $inputData = ['request-data' => 'yey?'];
        $expectedData = ['response-data' => 'yey!'];

        $response = $this->setExpectedGetRequest(
            $expectedData,
            self::BASE_URL . '/checkout/v3/orders/f60e69e8-464a-48c0-a452-6fd562540f37'
        );

        $response->expects(self::once())->method('getStatusCode')->willReturn(200);

        $this->authorizeRequest->initialize(
            [
                'render_url' => 'foobar',
                'base_url' => self::BASE_URL,
                'username' => self::USERNAME,
                'secret' => self::SECRET,
                'transactionReference' => 'f60e69e8-464a-48c0-a452-6fd562540f37',
            ]
        );

        $response = $this->authorizeRequest->sendData($inputData);

        self::assertInstanceOf(AuthorizeResponse::class, $response);
        self::assertSame($expectedData, $response->getData());
    }

    public function testSendDataWillRaiseExceptionOnErrorResponses()
    {
        $response = $this->createMock(ResponseInterface::class);
        $this->httpClient->expects(self::once())->method('request')->willReturn($response);

        $response->expects(self::once())->method('getStatusCode')->willReturn(401);

        $responseMessage = 'FooBar';
        $response->expects(self::once())->method('getReasonPhrase')->willReturn($responseMessage);

        $this->expectException(InvalidResponseException::class);
        $this->expectExceptionMessage($responseMessage);

        $this->authorizeRequest->sendData([]);
    }
}
