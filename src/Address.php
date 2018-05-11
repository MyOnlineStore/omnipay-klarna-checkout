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
            'organization_name' => null,
            'reference' => null,
            'attention' => null,
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

        return new self(\array_merge($defaults, \array_intersect_key($data, $defaults)));
    }

    /**
     * @param string[] $excludeKeyWithEmptyValue
     *
     * @return array
     */
    public function toArray(array $excludeKeyWithEmptyValue = [])
    {
        $excludeKeyWithEmptyValue = array_flip($excludeKeyWithEmptyValue);

        return array_filter(
            $this->getArrayCopy(),
            function ($value, $key) use ($excludeKeyWithEmptyValue) {
                if (!isset($excludeKeyWithEmptyValue[$key])) {
                    return true;
                }

                return !empty($value);
            },
            ARRAY_FILTER_USE_BOTH
        );
    }
}
