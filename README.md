# Omnipay: Klarna Checkout
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Scrutinizer Build](https://img.shields.io/scrutinizer/build/g/MyOnlineStore/omnipay-klarna-checkout.svg?style=flat-square)](https://github.com/MyOnlineStore/omnipay-klarna-checkout)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/MyOnlineStore/omnipay-klarna-checkout.svg?style=flat-square)](https://github.com/MyOnlineStore/omnipay-klarna-checkout)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/MyOnlineStore/omnipay-klarna-checkout.svg?style=flat-square)](https://github.com/MyOnlineStore/omnipay-klarna-checkout)

## Introduction

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Klarna Checkout support for Omnipay.

## Installation

To install, simply add it to your `composer.json` file:
```shell
$ composer require myonlinestore/omnipay-klarna-checkout
```

## Usage

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Quirks

Klarna Checkout requires an iframe to be rendered when authorizing payments. For this purpose a `render_url` parameter
was added to the [AuthorizeRequest](https://github.com/MyOnlineStore/omnipay-klarna-checkout/blob/authorize-request/src/Message/AuthorizeRequest.php)
class.

Providing a `render_url` will trigger a redirect to the given URL after the authorization process has been started at
Klarna. This is where you should fetch the corresponding transaction (using [FetchTransactionRequest](https://github.com/MyOnlineStore/omnipay-klarna-checkout/blob/authorize-request/src/Message/FetchTransactionRequest.php)),
and render the iframe. Example:

```php
$response = $gateway->fetchTransaction(['transactionReference' => 'foobar'])
    ->send();

echo $response->getData()['checkout']['html_snippet'];
```
Note: when submitting the form within the iframe, Klarna will redirect the client to the provided `return_url`.
