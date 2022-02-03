<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

final class WidgetOptions extends \ArrayObject
{
    /**
     * @param array $data
     *
     * @return WidgetOptions
     */
    public static function fromArray(array $data): WidgetOptions
    {
        $defaults = [
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
        ];

        return new self(\array_merge($defaults, \array_intersect_key($data, $defaults)));
    }
}
