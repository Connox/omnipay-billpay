# Omnipay: BillPay

**BillPay driver for the Omnipay PHP payment processing library**

[![License](https://poser.pugx.org/connox/omnipay-billpay/license)](https://packagist.org/packages/connox/omnipay-billpay)
[![Latest Stable Version](https://poser.pugx.org/connox/omnipay-billpay/v/stable)](https://packagist.org/packages/connox/omnipay-billpay)
[![Latest Unstable Version](https://poser.pugx.org/connox/omnipay-billpay/v/unstable)](https://packagist.org/packages/connox/omnipay-billpay)
[![Total Downloads](https://poser.pugx.org/connox/omnipay-billpay/downloads)](https://packagist.org/packages/connox/omnipay-billpay)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ConnoxGmbH/omnipay-billpay/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ConnoxGmbH/omnipay-billpay/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ConnoxGmbH/omnipay-billpay/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ConnoxGmbH/omnipay-billpay/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/ConnoxGmbH/omnipay-billpay/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ConnoxGmbH/omnipay-billpay/build-status/master)
[![Travis](https://img.shields.io/travis/ConnoxGmbH/omnipay-billpay.svg?style=flat)](https://travis-ci.org/ConnoxGmbH/omnipay-billpay)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.6+. This package implements BillPay support for Omnipay.


This package is still missing a lot of documentation on how to use it.
Not all feature from BillPay are implemented yet. Currently covered are:

- `Preauthorize` as `Gateway::authorize` and `Gateway::purchase`
- `InvoiceCreated` as `Gateway::invoiceCreated`
- `Cancel` as `Gateway::refund`

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "connox/omnipay-billpay": "~0.1"
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
