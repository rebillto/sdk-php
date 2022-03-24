<?php

include('setting.inc.php');

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
            'gatewayId' => 'c41e3612-6427-4e1b-bbfe-b9c138c34ab0'
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
            'gatewayId' => 'c41e3612-6427-4e1b-bbfe-b9c138c34ab0'
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
            'gatewayId' => 'c41e3612-6427-4e1b-bbfe-b9c138c34ab0'
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
    'organizationId' => '98b695d1-a0b2-40c4-b0ff-23894c55766e',
    'prices' => $prices,
    'customer' => (new \Rebill\SDK\Models\Shared\Profile)->setAttributes([
        'email' => 'usertest@test.com',
        'firstName' => 'Test',
        'lastName' => 'Name'
    ]),
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
])->create();

var_dump($checkout);
