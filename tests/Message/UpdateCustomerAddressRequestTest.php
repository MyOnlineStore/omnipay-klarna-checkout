<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\UpdateCustomerAddressRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\UpdateCustomerAddressResponse;
use Omnipay\Common\Exception\InvalidRequestException;

final class UpdateCustomerAddressRequestTest extends RequestTestCase
{
    /**
     * @var UpdateCustomerAddressRequest(
     */
    private $updateCustomerAddressRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->updateCustomerAddressRequest = new UpdateCustomerAddressRequest(
            $this->httpClient,
            $this->getHttpRequest()
        );
    }

    public function testGetDataWillThrowExceptionOnMissingData()
    {
        $this->setExpectedException(InvalidRequestException::class);

        $this->updateCustomerAddressRequest->getData();
    }

    public function testGetDataWillReturnCorrectData()
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
            "reference" => $reference,
            "attention" => $attention,
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
            "reference" => $reference,
            "attention" => $attention,
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

        $this->updateCustomerAddressRequest->initialize([
            'transactionReference' => 123,
            'billing_address' => $billingAddress,
            'shipping_address' => $shippingAddress,
        ]);

        self::assertEquals(
            [
                'shipping_address' => $shippingAddress,
                'billing_address' => $billingAddress,
            ],
            $this->updateCustomerAddressRequest->getData()
        );
    }

    public function testSendDataWillWillSendDataToKlarnaEndPointAndReturnCorrectResponse()
    {
        $transactionReference = 'foo';
        $data = ['foo' => 'bar'];
        $responseData = ['hello' => 'world'];

        $response = $this->setExpectedPatchRequest(
            $data,
            $responseData,
            sprintf('%s/ordermanagement/v1/orders/%s/customer-details', self::BASE_URL, $transactionReference)
        );

        $response->shouldReceive('getStatusCode')->andReturn(204);

        $this->updateCustomerAddressRequest->initialize(
            [
                'base_url' => self::BASE_URL,
                'merchant_id' => self::USERNAME,
                'secret' => self::SECRET,
                'transactionReference' => $transactionReference,
            ]
        );

        $updateCustomerAddressResponse = $this->updateCustomerAddressRequest->sendData($data);

        self::assertInstanceOf(UpdateCustomerAddressResponse::class, $updateCustomerAddressResponse);
        self::assertSame($transactionReference, $updateCustomerAddressResponse->getTransactionReference());
        self::assertSame(
            array_merge($responseData, ['order_id' => $transactionReference]),
            $updateCustomerAddressResponse->getData()
        );
        self::assertTrue($updateCustomerAddressResponse->isSuccessful());
    }
}
