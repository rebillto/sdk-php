# Rebill SDK for PHP

[![Latest Stable Version](https://poser.pugx.org/rebillto/sdk-php/v/stable)](https://packagist.org/packages/rebillto/sdk-php)
[![Total Downloads](https://poser.pugx.org/rebillto/sdk-php/downloads)](https://packagist.org/packages/rebillto/sdk-php)
[![License](https://poser.pugx.org/rebillto/sdk-php/license)](https://packagist.org/packages/rebillto/sdk-php)

This library provides developers with a simple set of bindings to help you integrate Rebill API to a website and start receiving payments.

## 💡 Requirements

PHP 7.1 or higher

## 💻 Installation 

First time using Rebill? Create your [Rebill account](https://www.rebill.to), if you don’t have one already.

1. Download [Composer](https://getcomposer.org/doc/00-intro.md) if not already installed

2. On your project directory run on the command line
`composer require "rebillto/sdk-php"`.

That's it! Rebill SDK has been successfully installed.

## 🌟 Getting Started
  
  Simple usage looks like:
  
```php

require('vendor/autoload.php');

\Rebill\SDK\Rebill::getInstance()->isDebug = true;
\Rebill\SDK\Rebill::getInstance()->setCallBackDebugLog(function ($data) {
    file_put_contents('logfile.log', '---------- '.date('c')." -------------- \n$data\n\n", FILE_APPEND | LOCK_EX);
});

\Rebill\SDK\Rebill::getInstance()->setProp([
    'access_token' => 'API_KEY_xxxxxxxxx',
    'orgAlias' => 'zzzz',
    'orgId' => 'xxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'
]);

```

For more examples, check the directory "examples"

## 🏻 License 

```
MIT license. Copyright (c) 2022 Rebill, Inc.
For more information, see the LICENSE file.
```
