# Omnipay: BillPay

> :bomb: UNDER DEVELOPMENT AND NOT USEABLE YET

**BillPay driver for the Omnipay PHP payment processing library**

[![Software License](https://img.shields.io/packagist/l/quillo/omnipay-billpay.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/quillo/omnipay-billpay.svg?style=flat-square)](https://packagist.org/packages/quillo/omnipay-billpay)
[![Total Downloads](https://img.shields.io/packagist/dt/quillo/omnipay-billpay.svg?style=flat-square)](https://packagist.org/packages/quillo/omnipay-billpay)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.6+. This package implements PayPal support for Omnipay.


This package is still in the early development stages and is not functioning at the moment 

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "quillo/omnipay-billpay": "dev-master"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Out Of Scope

Omnipay does not cover recurring payments or billing agreements, and so those features are not included in this package. Extensions to this gateway are always welcome. 
