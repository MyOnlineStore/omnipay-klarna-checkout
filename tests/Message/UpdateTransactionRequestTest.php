<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\UpdateTransactionRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\UpdateTransactionResponse;
use MyOnlineStore\Tests\Omnipay\KlarnaCheckout\ExpectedAuthorizationHeaderTrait;
use Omnipay\Common\Exception\InvalidRequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class UpdateTransactionRequestTest extends RequestTestCase
{
    use ItemDataTestTrait;
    use MerchantUrlsDataTestTrait;
    use ExpectedAuthorizationHeaderTrait;
    const TRANSACTION_REFERENCE = 1234;

    /**
     * @var UpdateTransactionRequest
     */
    private $updateTransactionRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->updateTransactionRequest = new UpdateTransactionRequest($this->httpClient, $this->getHttpRequest());
    }

    /**
     * @return array
     */
    public function merchantUrlDataProvider(): array
    {
        return [
            [$this->getMinimalValidMerchantUrlData(), $this->getMinimalExpectedMerchantUrlData()],
            [$this->getCompleteValidMerchantUrlData(), $this->getCompleteExpectedMerchantUrlData()],
        ];
    }

    public function testGetDataWillReturnCorrectData()
    {
        $this->updateTransactionRequest->initialize(
            [
                'amount' => '100.00',
                'tax_amount' => 21,
                'currency' => 'EUR',
                'transactionReference' => self::TRANSACTION_REFERENCE,
                'gui_minimal_confirmation' => true,
                'gui_autofocus' => false,
                'merchant_reference1' => '12345',
                'merchant_reference2' => 678,
                'purchase_country' => 'FR',
            ]
        );
        $this->updateTransactionRequest->setItems([$this->getItemMock()]);

        self::assertEquals(
            [
                'order_amount' => 10000,
                'order_tax_amount' => 2100,
                'order_lines' => [$this->getExpectedOrderLine()],
                'purchase_currency' => 'EUR',
                'gui' => ['options' => ['disable_autofocus', 'minimal_confirmation']],
                'merchant_reference1' => '12345',
                'merchant_reference2' => 678,
                'purchase_country' => 'FR',
            ],
            $this->updateTransactionRequest->getData()
        );
    }

    public function testGetDataWillReturnCorrectDataForEmptyCart()
    {
        $this->updateTransactionRequest->initialize(
            [
                'amount' => '100.00',
                'tax_amount' => 21,
                'currency' => 'EUR',
                'transactionReference' => self::TRANSACTION_REFERENCE,
                'gui_minimal_confirmation' => true,
                'gui_autofocus' => false,
                'merchant_reference1' => '12345',
                'merchant_reference2' => 678,
                'purchase_country' => 'FR',
            ]
        );

        self::assertEquals(
            [
                'order_amount' => 10000,
                'order_tax_amount' => 2100,
                'order_lines' => [],
                'purchase_currency' => 'EUR',
                'gui' => ['options' => ['disable_autofocus', 'minimal_confirmation']],
                'merchant_reference1' => '12345',
                'merchant_reference2' => 678,
                'purchase_country' => 'FR',
            ],
            $this->updateTransactionRequest->getData()
        );
    }

    public function testGetDataWillThrowExceptionForInvalidRequest()
    {
        $this->updateTransactionRequest->initialize([]);

        $this->expectException(InvalidRequestException::class);
        $this->updateTransactionRequest->getData();
    }

    public function testGetDataWithAddressWillReturnCorrectData()
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

        $this->updateTransactionRequest->initialize(
            [
                'locale' => 'nl_NL',
                'amount' => '100.00',
                'tax_amount' => 21,
                'currency' => 'EUR',
                'transactionReference' => self::TRANSACTION_REFERENCE,
                'gui_minimal_confirmation' => true,
                'gui_autofocus' => false,
                'merchant_reference1' => '12345',
                'merchant_reference2' => 678,
                'purchase_country' => 'NL',
            ]
        );
        $this->updateTransactionRequest->setItems([$this->getItemMock()]);
        $this->updateTransactionRequest->setShippingAddress($shippingAddress);
        $this->updateTransactionRequest->setBillingAddress($billingAddress);

        self::assertEquals(
            [
                'locale' => 'nl-NL',
                'order_amount' => 10000,
                'order_tax_amount' => 2100,
                'order_lines' => [$this->getExpectedOrderLine()],
                'purchase_country' => 'NL',
                'purchase_currency' => 'EUR',
                'gui' => ['options' => ['disable_autofocus', 'minimal_confirmation']],
                'merchant_reference1' => '12345',
                'merchant_reference2' => 678,
                'shipping_address' => $shippingAddress,
                'billing_address' => $billingAddress,
            ],
            $this->updateTransactionRequest->getData()
        );
    }

    public function testGetDataWithCustomerWillReturnCorrectData()
    {
        $customer = [
            'date_of_birth' => '1995-10-20',
            'type' => 'organization',
        ];

        $this->updateTransactionRequest->initialize(
            [
                'locale' => 'nl_NL',
                'amount' => '100.00',
                'tax_amount' => 21,
                'currency' => 'EUR',
                'transactionReference' => self::TRANSACTION_REFERENCE,
                'purchase_country' => 'FR',
            ]
        );
        $this->updateTransactionRequest->setItems([$this->getItemMock()]);
        $this->updateTransactionRequest->setCustomer($customer);

        self::assertEquals(
            [
                'locale' => 'nl-NL',
                'order_amount' => 10000,
                'order_tax_amount' => 2100,
                'order_lines' => [$this->getExpectedOrderLine()],
                'purchase_country' => 'FR',
                'purchase_currency' => 'EUR',
                'customer' => $customer,
            ],
            $this->updateTransactionRequest->getData()
        );
    }

    /**
     * @dataProvider merchantUrlDataProvider
     *
     * @param array $merchantUrlData
     * @param array $expectedMerchantUrls
     */
    public function testGetDataWithMerchantUrlsWillReturnCorrectData(
        $merchantUrlData,
        $expectedMerchantUrls
    ) {
        $this->updateTransactionRequest->initialize(
            array_merge(
                [
                    'amount' => '100.00',
                    'tax_amount' => 21,
                    'currency' => 'EUR',
                    'transactionReference' => self::TRANSACTION_REFERENCE,
                    'gui_minimal_confirmation' => true,
                    'gui_autofocus' => false,
                    'merchant_reference1' => '12345',
                    'merchant_reference2' => 678,
                    'purchase_country' => 'FR',
                    'returnUrl' => 'localhost/return',
                    'notifyUrl' => 'localhost/notify',
                    'termsUrl' => 'localhost/terms',
                ],
                $merchantUrlData
            )
        );
        $this->updateTransactionRequest->setItems([$this->getItemMock()]);

        self::assertEquals(
            [
                'order_amount' => 10000,
                'order_tax_amount' => 2100,
                'order_lines' => [$this->getExpectedOrderLine()],
                'purchase_currency' => 'EUR',
                'gui' => ['options' => ['disable_autofocus', 'minimal_confirmation']],
                'merchant_reference1' => '12345',
                'merchant_reference2' => '678',
                'purchase_country' => 'FR',
                'merchant_urls' => $expectedMerchantUrls,
            ],
            $this->updateTransactionRequest->getData()
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

        $this->updateTransactionRequest->initialize(
            [
                'locale' => 'nl_NL',
                'amount' => '100.00',
                'tax_amount' => 21,
                'currency' => 'EUR',
                'transactionReference' => self::TRANSACTION_REFERENCE,
                'gui_minimal_confirmation' => true,
                'gui_autofocus' => false,
                'merchant_reference1' => '12345',
                'merchant_reference2' => 678,
                'purchase_country' => 'DE',
            ]
        );
        $this->updateTransactionRequest->setItems([$this->getItemMock()]);
        $this->updateTransactionRequest->setWidgetOptions($widgetOptions);

        /** @noinspection PhpUnhandledExceptionInspection */
        /** @noinspection PhpUnhandledExceptionInspection */
        self::assertEquals(
            [
                'locale' => 'nl-NL',
                'order_amount' => 10000,
                'order_tax_amount' => 2100,
                'order_lines' => [$this->getExpectedOrderLine()],
                'purchase_country' => 'DE',
                'purchase_currency' => 'EUR',
                'gui' => ['options' => ['disable_autofocus', 'minimal_confirmation']],
                'merchant_reference1' => '12345',
                'merchant_reference2' => 678,
                'options' => $widgetOptions,
            ],
            $this->updateTransactionRequest->getData()
        );
    }

    public function testSendDataWillCreateOrderAndReturnResponse()
    {
        $inputData = ['request-data' => 'yey?'];
        $responseData = [];

        $this->setExpectedPostRequest(
            $inputData,
            $responseData,
            sprintf('%s/checkout/v3/orders/%s', self::BASE_URL, self::TRANSACTION_REFERENCE)
        );

        $this->updateTransactionRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'username' => self::USERNAME,
                'secret' => self::SECRET,
                'transactionReference' => self::TRANSACTION_REFERENCE,
            ]
        );

        $updateTransactionResponse = $this->updateTransactionRequest->sendData($inputData);

        self::assertInstanceOf(UpdateTransactionResponse::class, $updateTransactionResponse);
        self::assertSame($responseData, $updateTransactionResponse->getData());
    }

    public function testSendDataWillUpdateManagementCustomerDetailsAndFailUpdatingMerchantReferences()
    {
        $inputData = ['merchant_reference1' => 'foo'];

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $this->httpClient->expects(self::exactly(2))
            ->method('request')
            ->withConsecutive(
                [
                    'POST',
                    sprintf('%s/checkout/v3/orders/%s', self::BASE_URL, self::TRANSACTION_REFERENCE),
                    array_merge(['Content-Type' => 'application/json'], $this->getExpectedHeaders()),
                    \json_encode($inputData),
                ],
                [
                    'PATCH',
                    sprintf(
                        '%s/ordermanagement/v1/orders/%s/merchant-references',
                        self::BASE_URL,
                        self::TRANSACTION_REFERENCE
                    ),
                    array_merge(
                        ['Content-Type' => 'application/json'],
                        $this->getExpectedHeaders()
                    ),
                    \json_encode($inputData),
                ]
            )
            ->willReturn($response);

        $response->method('getBody')->willReturn($stream);
        $stream->expects(self::exactly(2))
            ->method('getContents')
            ->willReturnOnConsecutiveCalls(
                \json_encode(['error_code' => 'READ_ONLY_ORDER']),
                \json_encode(['error_code' => 'doomsday'])
            );

        $this->updateTransactionRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'username' => self::USERNAME,
                'secret' => self::SECRET,
                'transactionReference' => self::TRANSACTION_REFERENCE,
            ]
        );

        self::assertFalse($this->updateTransactionRequest->sendData($inputData)->isSuccessful());
    }

    public function testSendDataWillUpdateOrderManagementMerchantReferences()
    {
        $merchantReferencesData = ['merchant_reference1' => 'baz', 'merchant_reference2' => 'quz'];

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $this->httpClient->expects(self::exactly(2))
            ->method('request')
            ->withConsecutive(
                [
                    'POST',
                    sprintf('%s/checkout/v3/orders/%s', self::BASE_URL, self::TRANSACTION_REFERENCE),
                    array_merge(['Content-Type' => 'application/json'], $this->getExpectedHeaders()),
                    \json_encode($merchantReferencesData),
                ],
                [
                    'PATCH',
                    sprintf(
                        '%s/ordermanagement/v1/orders/%s/merchant-references',
                        self::BASE_URL,
                        self::TRANSACTION_REFERENCE
                    ),
                    array_merge(['Content-Type' => 'application/json'], $this->getExpectedHeaders()),
                    \json_encode($merchantReferencesData),
                ]
            )
            ->willReturn($response);

        $response->method('getBody')->willReturn($stream);
        $stream->expects(self::exactly(2))
            ->method('getContents')
            ->willReturnOnConsecutiveCalls(
                \json_encode(['error_code' => 'READ_ONLY_ORDER']),
                \json_encode([])
            );

        $this->updateTransactionRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'username' => self::USERNAME,
                'secret' => self::SECRET,
                'transactionReference' => self::TRANSACTION_REFERENCE,
            ]
        );

        $updateTransactionResponse = $this->updateTransactionRequest->sendData($merchantReferencesData);
        self::assertEmpty($updateTransactionResponse->getData());
        self::assertTrue($updateTransactionResponse->isSuccessful());
    }
}
