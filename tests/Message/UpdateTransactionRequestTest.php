<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\UpdateTransactionRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\UpdateTransactionResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class UpdateTransactionRequestTest extends RequestTestCase
{
    use ItemDataTestTrait;

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

    public function testGetDataWillThrowExceptionForInvalidRequest()
    {
        $this->updateTransactionRequest->initialize([]);

        $this->setExpectedException(InvalidRequestException::class);
        $this->updateTransactionRequest->getData();
    }

    public function testGetDataWillReturnCorrectData()
    {
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
            ]
        );
        $this->updateTransactionRequest->setItems([$this->getItemMock()]);

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
            ],
            $this->updateTransactionRequest->getData()
        );
    }

    public function testGetDataWithAddressWillReturnCorrectData()
    {
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
            ]
        );
        $this->updateTransactionRequest->setItems([$this->getItemMock()]);
        $this->updateTransactionRequest->setWidgetOptions($widgetOptions);

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
                'options' => $widgetOptions,
            ],
            $this->updateTransactionRequest->getData()
        );
    }

    public function testSendDataWillCreateOrderAndReturnResponse()
    {
        $inputData = ['request-data' => 'yey?'];
        $expectedData = [];

        $this->setExpectedPostRequest(
            $inputData,
            $expectedData,
            sprintf('%s/checkout/v3/orders/%s', self::BASE_URL, self::TRANSACTION_REFERENCE)
        );

        $this->updateTransactionRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'merchant_id' => self::MERCHANT_ID,
                'secret' => self::SECRET,
                'transactionReference' => self::TRANSACTION_REFERENCE,
            ]
        );

        $updateTransactionResponse = $this->updateTransactionRequest->sendData($inputData);

        self::assertInstanceOf(UpdateTransactionResponse::class, $updateTransactionResponse);
        self::assertSame($expectedData, $updateTransactionResponse->getData());
    }
}
