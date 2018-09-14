<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Customer;
use Omnipay\Tests\TestCase;

final class CustomerTest extends TestCase
{
    /**
     * @return array
     */
    public function dataProvider(): array
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
}
