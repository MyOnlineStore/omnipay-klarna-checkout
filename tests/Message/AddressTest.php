<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Address;
use Omnipay\Tests\TestCase;

final class AddressTest extends TestCase
{
    public function testFromArrayShoulReturnArrayWithCorrectKeys()
    {
        $familyName = 'foo';
        $givenName = 'bar';
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

        $address = Address::fromArray(
            [
                'family_name' => $familyName,
                'given_name' => $givenName,
                'email' => $email,
                'title' => $title,
                'street_address' => $streetAddress,
                'street_address2' => $streetAddress2,
                'street_name' => $streetName,
                'house_extension' => $houseExtension,
                'street_number' => $streetNumber,
                'postal_code' => $postalCode,
                'city' => $city,
                'region' => $region,
                'phone' => $phone,
                'country' => $country,
            ]
        );

        self::assertSame($givenName, $address['given_name']);
        self::assertSame($familyName, $address['family_name']);
        self::assertSame($email, $address['email']);
        self::assertSame($title, $address['title']);
        self::assertSame($streetAddress, $address['street_address']);
        self::assertSame($streetAddress2, $address['street_address2']);
        self::assertSame($streetName, $address['street_name']);
        self::assertSame($streetNumber, $address['street_number']);
        self::assertSame($houseExtension, $address['house_extension']);
        self::assertSame($postalCode, $address['postal_code']);
        self::assertSame($city, $address['city']);
        self::assertSame($region, $address['region']);
        self::assertSame($phone, $address['phone']);
        self::assertSame($country, $address['country']);
    }

    public function testFromArrayShoulReturnArrayWithCorrectKeysWithNullValuesForMissingKeys()
    {
        $familyName = 'foo';
        $givenName = 'bar';
        $email = 'foo@bar.com';
        $title = 'Mr.';
        $streetName = 'Foo Street';
        $houseExtension = 'C';
        $streetNumber = '1';
        $postalCode = '523354';
        $city = 'Oss';
        $region = 'NB';
        $phone = '24234234';
        $country = 'NL';

        $address = Address::fromArray(
            [
                'family_name' => $familyName,
                'given_name' => $givenName,
                'email' => $email,
                'title' => $title,
                'street_name' => $streetName,
                'house_extension' => $houseExtension,
                'street_number' => $streetNumber,
                'postal_code' => $postalCode,
                'city' => $city,
                'region' => $region,
                'phone' => $phone,
                'country' => $country,
            ]
        );

        self::assertSame($givenName, $address['given_name']);
        self::assertSame($familyName, $address['family_name']);
        self::assertSame($email, $address['email']);
        self::assertSame($title, $address['title']);
        self::assertNull($address['street_address']);
        self::assertNull($address['street_address2']);
        self::assertSame($streetName, $address['street_name']);
        self::assertSame($streetNumber, $address['street_number']);
        self::assertSame($houseExtension, $address['house_extension']);
        self::assertSame($postalCode, $address['postal_code']);
        self::assertSame($city, $address['city']);
        self::assertSame($region, $address['region']);
        self::assertSame($phone, $address['phone']);
        self::assertSame($country, $address['country']);
    }

    public function testFromArrayShoulReturnArrayWithCorrectKeysAndNotAddNoneExistingKeys()
    {
        $familyName = 'foo';
        $givenName = 'bar';
        $email = 'foo@bar.com';
        $title = 'Mr.';
        $streetName = 'Foo Street';
        $houseExtension = 'C';
        $streetNumber = '1';
        $postalCode = '523354';
        $city = 'Oss';
        $region = 'NB';
        $phone = '24234234';
        $country = 'NL';

        $address = Address::fromArray(
            [
                'family_name' => $familyName,
                'given_name' => $givenName,
                'email' => $email,
                'title' => $title,
                'street_name' => $streetName,
                'house_extension' => $houseExtension,
                'street_number' => $streetNumber,
                'postal_code' => $postalCode,
                'city' => $city,
                'region' => $region,
                'phone' => $phone,
                'country' => $country,
                'foo' => 'bar',
            ]
        );

        self::assertSame($givenName, $address['given_name']);
        self::assertSame($familyName, $address['family_name']);
        self::assertSame($email, $address['email']);
        self::assertSame($title, $address['title']);
        self::assertNull($address['street_address']);
        self::assertNull($address['street_address2']);
        self::assertSame($streetName, $address['street_name']);
        self::assertSame($streetNumber, $address['street_number']);
        self::assertSame($houseExtension, $address['house_extension']);
        self::assertSame($postalCode, $address['postal_code']);
        self::assertSame($city, $address['city']);
        self::assertSame($region, $address['region']);
        self::assertSame($phone, $address['phone']);
        self::assertSame($country, $address['country']);
        self::assertArrayNotHasKey('foo', $address);
    }
}
