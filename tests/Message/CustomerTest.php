<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Customer;
use Omnipay\Tests\TestCase;

final class CustomerTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param array $data
     * @param array $expectedOutcome
     */
    public function testFromArrayShoulReturnArrayWithCorrectKeys($data, $expectedOutcome)
    {
        self::assertEquals($expectedOutcome, Customer::fromArray($data)->getArrayCopy());
    }
    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            [
                [
                    'date_of_birth' => '1995-10-20',
                    'type' => 'organization',
                ],
                [
                    'date_of_birth' => '1995-10-20',
                    'type' => 'organization',
                ],
            ],
            [
                [],
                [
                    'date_of_birth' => null,
                    'type' => 'person',
                ],
            ],
            [
                ['foo' => 'bar'],
                [
                    'date_of_birth' => null,
                    'type' => 'person',
                ],
            ],
        ];
    }
}
