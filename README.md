# Omnipay: BillPay

> :bomb: UNDER DEVELOPMENT AND NOT USEABLE YET

**BillPay driver for the Omnipay PHP payment processing library**

[![License](https://poser.pugx.org/quillo/omnipay-billpay/license)](https://packagist.org/packages/quillo/omnipay-billpay)
[![Latest Stable Version](https://poser.pugx.org/quillo/omnipay-billpay/v/stable)](https://packagist.org/packages/quillo/omnipay-billpay)
[![Latest Unstable Version](https://poser.pugx.org/quillo/omnipay-billpay/v/unstable)](https://packagist.org/packages/quillo/omnipay-billpay)
[![Total Downloads](https://poser.pugx.org/quillo/omnipay-billpay/downloads)](https://packagist.org/packages/quillo/omnipay-billpay)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Quillo/omnipay-billpay/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Quillo/omnipay-billpay/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Quillo/omnipay-billpay/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Quillo/omnipay-billpay/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Quillo/omnipay-billpay/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Quillo/omnipay-billpay/build-status/master)
[![Travis](https://img.shields.io/travis/Quillo/omnipay-billpay.svg?style=flat-square)](https://travis-ci.org/Quillo/omnipay-billpay)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.6+. This package implements BillPay support for Omnipay.


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
