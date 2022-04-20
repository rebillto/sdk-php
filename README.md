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
    'user' => 'xxxxx',
    'pass' => 'yyyy',
    'orgAlias' => 'zzzz'
]);

$result = (new \Rebill\SDK\Models\Item)->setAttributes([
    'name' => 'Testing checkout',
    'description' => 'Test of Checkout',
    'metadata' => [
        'key_of_meta1' => 'example meta 1',
        'key_of_meta2' => 'example meta 2',
    ],
    'prices' => [
        (new \Rebill\SDK\Models\Price)->setAttributes([
            'amount' => 2.5,
            'type' => 'fixed',
            'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'days',
                'quantity' => 2
            ]),
            'freeTrial' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'days',
                'quantity' => 2
            ]),
            'repetitions' => 2,
            'currency' => 'USD',
            'description' => 'Example of Subscription with Free Trial',
            'gatewayId' => 'e8c80b48-606d-48b8-a059-ba7033ec53da'
        ]),
        (new \Rebill\SDK\Models\Price)->setAttributes([
            'amount' => 1.5,
            'type' => 'fixed',
            'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'days',
                'quantity' => 2
            ]),
            'repetitions' => 2,
            'currency' => 'USD',
            'description' => 'Example of Subscription',
            'gatewayId' => 'e8c80b48-606d-48b8-a059-ba7033ec53da'
        ]),
        (new \Rebill\SDK\Models\Price)->setAttributes([
            'amount' => 0.5,
            'type' => 'fixed',
            'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'days',
                'quantity' => 1
            ]),
            'repetitions' => 1,
            'currency' => 'USD',
            'description' => 'Example of Unique Payment',
            'gatewayId' => 'e8c80b48-606d-48b8-a059-ba7033ec53da'
        ]),
    ]
])->create();

$prices = [];
foreach ($result->prices as $p) {
    $prices[] = (new \Rebill\SDK\Models\Shared\CheckoutPrice)->setAttributes([
        'id' => $p->id,
        'quantity' => 1
    ]);
}

$checkout = (new \Rebill\SDK\Models\Checkout)->setAttributes([
    'organizationId' => '028b29da-682f-4e3e-93bc-9236fd871138',
    'prices' => $prices,
    'customer' => (new \Rebill\SDK\Models\Shared\CustomerCheckout)->setAttributes([
        'email' => 'usertest@test.com',
        'firstName' => 'Test',
        'lastName' => 'Name',
        'card' => (new \Rebill\SDK\Models\Card)->setAttributes([
            'cardNumber' => '4111111111111111',
            'cardHolder' => [
                'name' => 'Test Card',
                'identification' => [
                    'type' => 'DNI',
                    'value' => '1111111111'
                ]
            ],
            'securityCode' => '123',
            'expiration' => [
                'month' => 12,
                'year' => 2030
            ],
        ])
    ])
])->create();

if ($checkout) {
    var_dump($checkout->paidBags[0]->payment->status);
} else {
    echo "Payment error, see log file...";
}

```

For more examples, check the directory "examples"

## 🏻 License 

```
MIT license. Copyright (c) 2022 Rebill, Inc.
For more information, see the LICENSE file.
```
