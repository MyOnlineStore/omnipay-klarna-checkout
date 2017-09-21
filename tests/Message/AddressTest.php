<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Address;
use Omnipay\Tests\TestCase;

final class AddressTest extends TestCase
{
    const FAMILY_NAME = 'foo';
    const GIVEN_NAME = 'bar';
    const EMAIL = 'foo@bar.com';
    const TITLE = 'Mr.';
    const STREET_ADDRESS_1 = 'Foo Street 1';
    const STREET_ADDRESS_2 = 'App. 12A';
    const STREET = 'Foo Street';
    const HOUSE_EXTENSION = 'C';
    const STREET_NUMBER = '1';
    const POSTAL_CODE = '523354';
    const CITY = 'Oss';
    const REGION = 'NB';
    const PHONE = '24234234';
    const COUNTRY = 'NL';

    /**
     * @dataProvider dataProvider
     *
     * @param array $data
     * @param array $expectedOutcome
     */
    public function testFromArrayShoulReturnArrayWithCorrectKeys($data, $expectedOutcome)
    {
        self::assertEquals($expectedOutcome, Address::fromArray($data)->getArrayCopy());
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            [
                [
                    'family_name' => self::FAMILY_NAME,
                    'given_name' => self::GIVEN_NAME,
                    'email' => self::EMAIL,
                    'title' => self::TITLE,
                    'street_address' => self::STREET_ADDRESS_1,
                    'street_address2' => self::STREET_ADDRESS_2,
                    'street_name' => self::STREET,
                    'house_extension' => self::HOUSE_EXTENSION,
                    'street_number' => self::STREET_NUMBER,
                    'postal_code' => self::POSTAL_CODE,
                    'city' => self::CITY,
                    'region' => self::REGION,
                    'phone' => self::PHONE,
                    'country' => self::COUNTRY,
                ],
                [
                    'family_name' => self::FAMILY_NAME,
                    'given_name' => self::GIVEN_NAME,
                    'email' => self::EMAIL,
                    'title' => self::TITLE,
                    'street_address' => self::STREET_ADDRESS_1,
                    'street_address2' => self::STREET_ADDRESS_2,
                    'street_name' => self::STREET,
                    'house_extension' => self::HOUSE_EXTENSION,
                    'street_number' => self::STREET_NUMBER,
                    'postal_code' => self::POSTAL_CODE,
                    'city' => self::CITY,
                    'region' => self::REGION,
                    'phone' => self::PHONE,
                    'country' => self::COUNTRY,
                ],
            ],
            [
                [
                    'family_name' => self::FAMILY_NAME,
                    'given_name' => self::GIVEN_NAME,
                    'email' => self::EMAIL,
                    'title' => self::TITLE,
                    'street_name' => self::STREET,
                    'house_extension' => self::HOUSE_EXTENSION,
                    'street_number' => self::STREET_NUMBER,
                    'postal_code' => self::POSTAL_CODE,
                    'city' => self::CITY,
                    'region' => self::REGION,
                    'phone' => self::PHONE,
                    'country' => self::COUNTRY,
                ],
                [
                    'family_name' => self::FAMILY_NAME,
                    'given_name' => self::GIVEN_NAME,
                    'email' => self::EMAIL,
                    'title' => self::TITLE,
                    'street_address' => null,
                    'street_address2' => null,
                    'street_name' => self::STREET,
                    'house_extension' => self::HOUSE_EXTENSION,
                    'street_number' => self::STREET_NUMBER,
                    'postal_code' => self::POSTAL_CODE,
                    'city' => self::CITY,
                    'region' => self::REGION,
                    'phone' => self::PHONE,
                    'country' => 'NL',
                ],
            ],
            [
                [
                    'family_name' => self::FAMILY_NAME,
                    'given_name' => self::GIVEN_NAME,
                    'email' => self::EMAIL,
                    'title' => self::TITLE,
                    'street_name' => self::STREET,
                    'house_extension' => self::HOUSE_EXTENSION,
                    'street_number' => self::STREET_NUMBER,
                    'postal_code' => self::POSTAL_CODE,
                    'city' => self::CITY,
                    'region' => self::REGION,
                    'phone' => self::PHONE,
                    'country' => self::COUNTRY,
                    self::FAMILY_NAME => self::GIVEN_NAME,
                ],
                [
                    'family_name' => self::FAMILY_NAME,
                    'given_name' => self::GIVEN_NAME,
                    'email' => self::EMAIL,
                    'title' => self::TITLE,
                    'street_address' => null,
                    'street_address2' => null,
                    'street_name' => self::STREET,
                    'house_extension' => self::HOUSE_EXTENSION,
                    'street_number' => self::STREET_NUMBER,
                    'postal_code' => self::POSTAL_CODE,
                    'city' => self::CITY,
                    'region' => self::REGION,
                    'phone' => self::PHONE,
                    'country' => self::COUNTRY,
                ],
            ],
        ];
    }
}
