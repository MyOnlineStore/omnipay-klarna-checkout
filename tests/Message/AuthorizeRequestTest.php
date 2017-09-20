<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AuthorizeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AuthorizeResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class AuthorizeRequestTest extends RequestTestCase
{
    use ItemDataTestTrait;

    /**
     * @var AuthorizeRequest
     */
    private $authorizeRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->authorizeRequest = new AuthorizeRequest($this->httpClient, $this->getHttpRequest());
    }

    /**
     * @return array
     */
    public function invalidRequestDataProvider()
    {
        $data = [
            'amount' => true,
            'currency' => true,
            'items' => [],
            'locale' => true,
            'notifyUrl' => true,
            'returnUrl' => true,
            'tax_amount' => true,
            'terms_url' => true,
        ];

        $cases = [];

        foreach ($data as $key => $value) {
            $cases[] = [array_diff_key($data, [$key => $value])];
        }

        return $cases;
    }

    /**
     * @dataProvider invalidRequestDataProvider
     *
     * @param array $requestData
     */
    public function testGetDataWillThrowExceptionForInvalidRequest(array $requestData)
    {
        $this->authorizeRequest->initialize($requestData);

        $this->setExpectedException(InvalidRequestException::class);
        $this->authorizeRequest->getData();
    }

    public function testGetDataWillReturnCorrectData()
    {
        $this->authorizeRequest->initialize(
            [
                'locale' => 'nl_NL',
                'amount' => '100.00',
                'tax_amount' => 21,
                'returnUrl' => 'localhost/return',
                'notifyUrl' => 'localhost/notify',
                'termsUrl' => 'localhost/terms',
                'currency' => 'EUR',
                'validationUrl' => 'localhost/validate',
            ]
        );
        $this->authorizeRequest->setItems([$this->getItemMock()]);

        self::assertEquals(
            [
                'locale' => 'nl-NL',
                'order_amount' => 10000,
                'order_tax_amount' => 2100,
                'order_lines' => [$this->getExpectedOrderLine()],
                'merchant_urls' => [
                    'checkout' => 'localhost/return',
                    'confirmation' => 'localhost/return',
                    'push' => 'localhost/notify',
                    'terms' => 'localhost/terms',
                    'validation' => 'localhost/validate',
                ],
                'purchase_country' => 'NL',
                'purchase_currency' => 'EUR',
            ],
            $this->authorizeRequest->getData()
        );
    }

    public function testGetDataWithAddressesWillReturnCorrectData()
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

        $this->authorizeRequest->initialize(
            [
                'locale' => 'nl_NL',
                'amount' => '100.00',
                'tax_amount' => 21,
                'returnUrl' => 'localhost/return',
                'notifyUrl' => 'localhost/notify',
                'termsUrl' => 'localhost/terms',
                'currency' => 'EUR',
                'validationUrl' => 'localhost/validate',
            ]
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
                'merchant_urls' => [
                    'checkout' => 'localhost/return',
                    'confirmation' => 'localhost/return',
                    'push' => 'localhost/notify',
                    'terms' => 'localhost/terms',
                    'validation' => 'localhost/validate',
                ],
                'purchase_country' => 'NL',
                'purchase_currency' => 'EUR',
                'shipping_address' => $shippingAddress,
                'billing_address' => $billingAddress,
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
        ];

        $this->authorizeRequest->initialize(
            [
                'locale' => 'nl_NL',
                'amount' => '100.00',
                'tax_amount' => 21,
                'returnUrl' => 'localhost/return',
                'notifyUrl' => 'localhost/notify',
                'termsUrl' => 'localhost/terms',
                'currency' => 'EUR',
                'validationUrl' => 'localhost/validate',
            ]
        );
        $this->authorizeRequest->setItems([$this->getItemMock()]);
        $this->authorizeRequest->setWidgetOptions($widgetOptions);

        self::assertEquals(
            [
                'locale' => 'nl-NL',
                'order_amount' => 10000,
                'order_tax_amount' => 2100,
                'order_lines' => [$this->getExpectedOrderLine()],
                'merchant_urls' => [
                    'checkout' => 'localhost/return',
                    'confirmation' => 'localhost/return',
                    'push' => 'localhost/notify',
                    'terms' => 'localhost/terms',
                    'validation' => 'localhost/validate',
                ],
                'purchase_country' => 'NL',
                'purchase_currency' => 'EUR',
                'options' => $widgetOptions,
            ],
            $this->authorizeRequest->getData()
        );
    }

    public function testSendDataWillCreateOrderAndReturnResponse()
    {
        $inputData = ['request-data' => 'yey?'];
        $expectedData = ['response-data' => 'yey!'];

        $this->setExpectedPostRequest($inputData, $expectedData, self::BASE_URL.'/checkout/v3/orders');

        $this->authorizeRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'merchant_id' => self::MERCHANT_ID,
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

        $this->setExpectedGetRequest(
            $expectedData,
            self::BASE_URL.'/checkout/v3/orders/f60e69e8-464a-48c0-a452-6fd562540f37'
        );

        $this->authorizeRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'merchant_id' => self::MERCHANT_ID,
                'secret' => self::SECRET,
                'transactionReference' => 'f60e69e8-464a-48c0-a452-6fd562540f37',
            ]
        );

        $response = $this->authorizeRequest->sendData($inputData);

        self::assertInstanceOf(AuthorizeResponse::class, $response);
        self::assertSame($expectedData, $response->getData());
    }
}
