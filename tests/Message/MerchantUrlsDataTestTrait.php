<?php
declare(strict_types=1);

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

trait MerchantUrlsDataTestTrait
{
    /**
     * @return array
     */
    public function getCompleteExpectedMerchantUrlData(): array
    {
        return [
            'address_update' => 'localhost/address-update',
            'cancellation_terms' => 'localhost/cancellation-terms',
            'checkout' => 'localhost/return',
            'confirmation' => 'localhost/confirm',
            'push' => 'localhost/notify',
            'terms' => 'localhost/terms',
            'validation' => 'localhost/validate',
        ];
    }

    /**
     * @return array
     */
    public function getCompleteValidMerchantUrlData(): array
    {
        return [
            'addressUpdateUrl' => 'localhost/address-update',
            'cancellationTermsUrl' => 'localhost/cancellation-terms',
            'returnUrl' => 'localhost/return',
            'confirmationUrl' => 'localhost/confirm',
            'notifyUrl' => 'localhost/notify',
            'termsUrl' => 'localhost/terms',
            'validationUrl' => 'localhost/validate',
        ];
    }

    /**
     * @return array
     */
    public function getMinimalExpectedMerchantUrlData(): array
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
    public function getMinimalValidMerchantUrlData(): array
    {
        return [
            'returnUrl' => 'localhost/return',
            'notifyUrl' => 'localhost/notify',
            'termsUrl' => 'localhost/terms',
            'validationUrl' => 'localhost/validate',
        ];
    }
}
