# Omnipay: Klarna Checkout
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Scrutinizer Build](https://img.shields.io/scrutinizer/build/g/MyOnlineStore/omnipay-klarna-checkout.svg?style=flat-square)](https://github.com/MyOnlineStore/omnipay-klarna-checkout)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/MyOnlineStore/omnipay-klarna-checkout.svg?style=flat-square)](https://github.com/MyOnlineStore/omnipay-klarna-checkout)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/MyOnlineStore/omnipay-klarna-checkout.svg?style=flat-square)](https://github.com/MyOnlineStore/omnipay-klarna-checkout)

## Introduction

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.6+. This package implements Klarna Checkout support for Omnipay.

## Installation

To install, simply add it to your `composer.json` file:
```shell
$ composer require myonlinestore/omnipay-klarna-checkout
```

## Initialization

First, create the Omnipay gateway:
```php
$gateway = Omnipay\Omnipay::create('\MyOnlineStore\Omnipay\KlarnaCheckout\Gateway');
// or
$gateway = new MyOnlineStore\Omnipay\KlarnaCheckout\Gateway(/* $httpClient, $httpRequest */);
```
Then, initialize it with the correct credentials:
```php
$gateway->initialize([
    'username' => $username, 
    'secret' => $secret,
    'api_region' => $region, // Optional, may be Gateway::API_VERSION_EUROPE (default) or Gateway::API_VERSION_NORTH_AMERICA
    'testMode' => false // Optional, default: true
]);
// or 
$gateway->setUsername($username);
$gateway->setSecret($secret);
$gateway->setApiRegion($region);
```

## Usage

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

### General flow

1. [Create a Klarna order](#authorize)
2. [Update transaction](#update-transaction) (if required)
3. [Render the Iframe](#render-iframe)
4. Respond to redirects to `checkoutUrl` or `confirmation_url`
5. Respond to [checkout callbacks](https://developers.klarna.com/api/#checkout-api-callbacks-callbacks)
6. Respond to the request to `push_url` (indicates order was completed by client)
   with a [ackowledgement](#acknowledge)
7. [Extend authorization](#extend-authorization) (if required)
8. [Update the merchant address](#update-merchant-address) (if required)
9. Perform one or more [capture(s)](#capture), [refund(s)](#refund) or [void](#void) operations  

### Authorize

To create a new order, use the `authorize` method:
```php
$data = [
    'amount' => 100,
    'tax_amount' => 20,
    'currency' => 'SEK',
    'locale' => 'SE',
    'purchase_country' => 'SE',
    
    'notify_url' => '', // https://developers.klarna.com/api/#checkout-api__ordermerchant_urls__validation
    'return_url' => '', // https://developers.klarna.com/api/#checkout-api__ordermerchant_urls__checkout
    'terms_url' => '', // https://developers.klarna.com/api/#checkout-api__ordermerchant_urls__terms
    'validation_url' => '', // https://developers.klarna.com/api/#checkout-api__ordermerchant_urls__validation

    'items' => [
        [
            'type' => 'physical',
            'name' => 'Shirt',
            'quantity' => 1,
            'tax_rate' => 25,
            'price' => 100,
            'unit_price' => 100,
            'total_tax_amount' => 20,
        ],
    ],
];

$response = $gateway->authorize($data)->send()->getData();
```
This will return the order details as well as the checkout HTML snippet to render on your site.

[API documentation](https://github.com/MyOnlineStore/omnipay-klarna-checkout/blob/master/src/Message/AuthorizeRequest.php)

## Render Iframe

Klarna Checkout requires an iframe to be rendered when authorizing payments:

```php
$response = $gateway->fetchTransaction(['transactionReference' => 'a5bec272-d68d-4df9-9fdd-8e35e51f92ab'])
    ->send();

echo $response->getData()['checkout']['html_snippet'];
```

After submitting the form within the iframe, 
Klarna will redirect the client to the provided `confirmation_url` (success)
or `checkout_url` (failure)`.

### Update transaction

While an order has not been authorized (completed) by the client, it may be updated:

```php
$response = $gateway->updateTransaction([
    'transactionReference' => 'a5bec272-d68d-4df9-9fdd-8e35e51f92ab',
    'amount'           => 200,
    'tax_amount'       => 40,
    'currency'         => 'SEK',
    'locale'           => 'SE',
    'purchase_country' => 'SE',
    'items' => [/*...*/],
])->send();
```

The response will contain the updated order data.

[API documentation](https://developers.klarna.com/api/#checkout-api-update-an-order)

### Extend authorization

Klarna order authorization is valid until a specific date, and may be extended (up to a maximum of 180 days).
The updated expiration date may then be retrieved with a [fetch](#fetch) request 

```php
if ($gateway->extendAuthorization(['transactionReference' => 'a5bec272-d68d-4df9-9fdd-8e35e51f92ab'])->send()->isSuccessful()) {
    $expiration = new \DateTimeImmutable(
        $gateway->fetchTransaction(['transactionReference' => 'a5bec272-d68d-4df9-9fdd-8e35e51f92ab'])
            ->send()
            ->getData()['management']['expires_at']
    );
}
```

[API documentation](https://developers.klarna.com/api/#order-management-api-extend-authorization-time)

### Capture

```php
$success = $gateway->capture([
    'transactionReference' => 'a5bec272-d68d-4df9-9fdd-8e35e51f92ab',
    'amount' => '995',
])->send()
->isSuccessful();
```

[API documentation](https://developers.klarna.com/api/#order-management-api-create-capture)

### Fetch

A Klarna order is initially available through the checkout API. After it has been authorized, it will be available
through the Order management API (and will, after some time, no longer be available in the checkout API).
This fetch request will first check whether the order exitst in the checkout API.
If that is not the case, or the status indicates the order is finished,
it will also fetch the order from the order management API 

```php
$response = $gateway->fetchTransaction(['transactionReference' => 'a5bec272-d68d-4df9-9fdd-8e35e51f92ab'])
    ->send();

$success = $response->isSuccessful();
$checkoutData = $response->getData()['checkout'] ?? [];
$managementData = $response->getData()['management'] ?? [];
```
API documentation |
[Checkout](https://developers.klarna.com/api/#checkout-api-retrieve-an-order) |
[Order management](https://developers.klarna.com/api/#order-management-api-get-order)

### Acknowlegde

Acknowledge a completed order 

```php
$success = $gateway->acknowledge(['transactionReference' => 'a5bec272-d68d-4df9-9fdd-8e35e51f92ab'])
    ->send()
    ->isSuccessful();
```

[API documentation](https://developers.klarna.com/api/#order-management-api-acknowledge-order)

### Refund

```php
$success = $gateway->refund([
    'transactionReference' => 'a5bec272-d68d-4df9-9fdd-8e35e51f92ab',
    'amount' => '995',
])->send()
->isSuccessful();
```

[API documentation](https://developers.klarna.com/api/#order-management-api-create-a-refund)

### Void

You may release the remaining authorized amount. Specifying a specific amount is not possible. 

```php
$success = $gateway->void(['transactionReference' => 'a5bec272-d68d-4df9-9fdd-8e35e51f92ab'])
    ->send()
    ->isSuccessful();
```

[API documentation](https://developers.klarna.com/api/#order-management-api-release-remaining-authorization)

### Update customer address

This may be used when updating customer address details *after* the order has been authorized.
Success op this operation is subject to a risk assessment by Klarna. Both addresses are required parameters.

```php
$success = $gateway->refund([
    'transactionReference' => 'a5bec272-d68d-4df9-9fdd-8e35e51f92ab',
    'shipping_address' => [
        'given_name'=> 'Klara',
        'family_name'=> 'Joyce',
        'title'=> 'Mrs',
        'street_address'=> 'Apartment 10',
        'street_address2'=> '1 Safeway',
        'postal_code'=> '12345',
        'city'=> 'Knoxville',
        'region'=> 'TN',
        'country'=> 'us',
        'email'=> 'klara.joyce@klarna.com',
        'phone'=> '1-555-555-5555'
    ],
    'billing_address' => [
        'given_name'=> 'Klara',
        'family_name'=> 'Joyce',
        'title'=> 'Mrs',
        'street_address'=> 'Apartment 10',
        'street_address2'=> '1 Safeway',
        'postal_code'=> '12345',
        'city'=> 'Knoxville',
        'region'=> 'TN',
        'country'=> 'us',
        'email'=> 'klara.joyce@klarna.com',
        'phone'=> '1-555-555-5555'
    ],
])->send()
->isSuccessful();
```

[API documentation](https://developers.klarna.com/api/#order-management-api-update-customer-addresses)

### Update merchant reference(s)

If an order has been authorized by the client, its merchant references may be updated:

```php
$response = $gateway->updateMerchantReferences([
    'merchant_reference1' => 'foo',
    'merchant_reference2' => 'bar',
])->send();
```

[API documentation](https://developers.klarna.com/api/#order-management-api-update-merchant-references)


## Units

Klarna expresses amounts in minor units as described [here](https://developers.klarna.com/api/#data-types).
