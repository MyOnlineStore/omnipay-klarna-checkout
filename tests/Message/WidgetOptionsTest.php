<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\WidgetOptions;
use Omnipay\Tests\TestCase;

final class WidgetOptionsTest extends TestCase
{
    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                [
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
                ],
                [
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
                    'allow_global_billing_countries' => false,
                ],
            ],
            [
                [],
                [
                    'acquiring_channel' => 'eCommerce',
                    'allow_separate_shipping_address' => false,
                    'color_button' => null,
                    'color_button_text' => null,
                    'color_checkbox' => null,
                    'color_checkbox_checkmark' => null,
                    'color_header' => null,
                    'color_link' => null,
                    'date_of_birth_mandatory' => false,
                    'shipping_details' => null,
                    'title_mandatory' => false,
                    'additional_checkbox' => null,
                    'radius_border' => null,
                    'show_subtotal_detail' => false,
                    'require_validate_callback_success' => false,
                    'allow_global_billing_countries' => false,
                ],
            ],
            [
                ['foo' => 'bar'],
                [
                    'acquiring_channel' => 'eCommerce',
                    'allow_separate_shipping_address' => false,
                    'color_button' => null,
                    'color_button_text' => null,
                    'color_checkbox' => null,
                    'color_checkbox_checkmark' => null,
                    'color_header' => null,
                    'color_link' => null,
                    'date_of_birth_mandatory' => false,
                    'shipping_details' => null,
                    'title_mandatory' => false,
                    'additional_checkbox' => null,
                    'radius_border' => null,
                    'show_subtotal_detail' => false,
                    'require_validate_callback_success' => false,
                    'allow_global_billing_countries' => false,
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
        self::assertEquals($expectedOutcome, WidgetOptions::fromArray($data)->getArrayCopy());
    }
}
