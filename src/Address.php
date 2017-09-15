<?php

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

final class Address extends \ArrayObject
{
    /**
     * @param array $data
     *
     * @return Address
     */
    public static function fromArray(array $data)
    {
        $defaults = [
            'family_name' => null,
            'given_name' => null,
            'email' => null,
            'title' => null,
            'street_address' => null,
            'street_address2' => null,
            'street_name' => null,
            'house_extension' => null,
            'street_number' => null,
            'postal_code' => null,
            'city' => null,
            'region' => null,
            'phone' => null,
            'country' => null,
        ];

        return new self(array_merge($defaults, array_intersect_key($data, $defaults)));
    }
}
