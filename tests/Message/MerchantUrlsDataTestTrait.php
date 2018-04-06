<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

trait MerchantUrlsDataTestTrait
{
    /**
     * @return array
     */
    public function getCompleteExpectedMerchantUrlData()
    {
        return [
            'address_update' => 'localhost/address-update',
            'cancellation_terms' => 'localhost/cancellation-terms',
            'checkout' => 'localhost/return',
            'confirmation' => 'localhost/return',
            'push' => 'localhost/notify',
            'terms' => 'localhost/terms',
            'validation' => 'localhost/validate',
        ];
    }

    /**
     * @return array
     */
    public function getCompleteValidMerchantUrlData()
    {
        return [
            'addressUpdateUrl' => 'localhost/address-update',
            'cancellationTermsUrl' => 'localhost/cancellation-terms',
            'returnUrl' => 'localhost/return',
            'notifyUrl' => 'localhost/notify',
            'termsUrl' => 'localhost/terms',
            'validationUrl' => 'localhost/validate',
        ];
    }

    /**
     * @return array
     */
    public function getMinimalExpectedMerchantUrlData()
    {
        return [
            'checkout' => 'localhost/return',
            'confirmation' => 'localhost/return',
            'push' => 'localhost/notify',
            'terms' => 'localhost/terms',
            'validation' => 'localhost/validate',
        ];
    }

    /**
     * @return array
     */
    public function getMinimalValidMerchantUrlData()
    {
        return [
            'returnUrl' => 'localhost/return',
            'notifyUrl' => 'localhost/notify',
            'termsUrl' => 'localhost/terms',
            'validationUrl' => 'localhost/validate',
        ];
    }
}
