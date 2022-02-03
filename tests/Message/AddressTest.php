<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Address;
use Omnipay\Tests\TestCase;

final class AddressTest extends TestCase
{
    public const ATTENTION = 'quz';
    public const CITY = 'Oss';
    public const COUNTRY = 'NL';
    public const EMAIL = 'foo@bar.com';
    public const FAMILY_NAME = 'foo';
    public const GIVEN_NAME = 'bar';
    public const HOUSE_EXTENSION = 'C';
    public const ORGANIZATION_NAME = 'Foo Inc.';
    public const PHONE = '24234234';
    public const POSTAL_CODE = '523354';
    public const REFERENCE = 'ref';
    public const REGION = 'NB';
    public const STREET = 'Foo Street';
    public const STREET_ADDRESS_1 = 'Foo Street 1';
    public const STREET_ADDRESS_2 = 'App. 12A';
    public const STREET_NUMBER = '1';
    public const TITLE = 'Mr.';

    /**
     * @return array
     */
    public function fromArrayDataProvider(): array
    {
        return [
            [
                [
                    'organization_name' => self::ORGANIZATION_NAME,
                    'reference' => self::REFERENCE,
                    'attention' => self::ATTENTION,
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
                    'organization_name' => self::ORGANIZATION_NAME,
                    'reference' => self::REFERENCE,
                    'attention' => self::ATTENTION,
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
                    'organization_name' => self::ORGANIZATION_NAME,
                    'reference' => self::REFERENCE,
                    'attention' => self::ATTENTION,
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
                    'organization_name' => self::ORGANIZATION_NAME,
                    'reference' => self::REFERENCE,
                    'attention' => self::ATTENTION,
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
                    'organization_name' => self::ORGANIZATION_NAME,
                    'reference' => self::REFERENCE,
                    'attention' => self::ATTENTION,
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
                    'organization_name' => self::ORGANIZATION_NAME,
                    'reference' => self::REFERENCE,
                    'attention' => self::ATTENTION,
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

    /**
     * @dataProvider fromArrayDataProvider
     *
     * @param array $data
     * @param array $expectedOutcome
     */
    public function testFromArrayShoulReturnArrayWithCorrectKeys(array $data, array $expectedOutcome)
    {
        self::assertEquals($expectedOutcome, Address::fromArray($data)->getArrayCopy());
    }

    /**
     * @return array
     */
    public function toArrayDataProvider(): array
    {
        return [
            [
                [
                    'organization_name' => self::ORGANIZATION_NAME,
                    'reference' => self::REFERENCE,
                    'attention' => self::ATTENTION,
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
                    'organization_name' => self::ORGANIZATION_NAME,
                    'reference' => self::REFERENCE,
                    'attention' => self::ATTENTION,
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
                [],
            ],
            [
                [
                    'organization_name' => '',
                    'reference' => false,
                    'attention' => self::ATTENTION,
                    'family_name' => self::FAMILY_NAME,
                    'given_name' => self::GIVEN_NAME,
                    'email' => self::EMAIL,
                    'title' => null,
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
                    'attention' => self::ATTENTION,
                    'family_name' => self::FAMILY_NAME,
                    'given_name' => self::GIVEN_NAME,
                    'email' => self::EMAIL,
                    'street_name' => self::STREET,
                    'house_extension' => self::HOUSE_EXTENSION,
                    'street_number' => self::STREET_NUMBER,
                    'postal_code' => self::POSTAL_CODE,
                    'city' => self::CITY,
                    'region' => self::REGION,
                    'phone' => self::PHONE,
                    'country' => self::COUNTRY,
                    'street_address' => null,
                    'street_address2' => null,
                ],
                ['title', 'organization_name', 'reference'],
            ],
        ];
    }
}
