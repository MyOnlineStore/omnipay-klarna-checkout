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
            'total_tax_amount' => 20
        ]
    ]
];

$response = $gateway->authorize($data)->send()->getData();
```
This will return the order details as well as the checkout HTML snippet to render on your site.

## Units

Klarna expresses amounts in minor units as described [here](https://developers.klarna.com/api/#data-types).

## Quirks

Klarna Checkout requires an iframe to be rendered when authorizing payments. For this purpose a `render_url` parameter
was added to the [AuthorizeRequest](https://github.com/MyOnlineStore/omnipay-klarna-checkout/blob/master/src/Message/AuthorizeRequest.php)
class.

Providing a `render_url` will trigger a redirect to the given URL after the authorization process has been started at
Klarna. This is where you should fetch the corresponding transaction (using [FetchTransactionRequest](https://github.com/MyOnlineStore/omnipay-klarna-checkout/blob/master/src/Message/FetchTransactionRequest.php)),
and render the iframe. Example:

```php
$response = $gateway->fetchTransaction(['transactionReference' => 'foobar'])
    ->send();

echo $response->getData()['checkout']['html_snippet'];
```
Note: when submitting the form within the iframe, Klarna will redirect the client to the provided `return_url`.
