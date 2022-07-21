<?php

include('setting.inc.php');
define('REBILL_GATEWAY_ID', 'b7249fce-727b-4516-8258-36046c4c5716');
define('REBILL_GATEWAY_CURRENCY', 'USD');

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
            'description' => 'Example of Subscription with Free Trial',
            'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'days',
                'quantity' => 2
            ]),
            'freeTrial' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'days',
                'quantity' => 2
            ]),
            'repetitions' => 2,
            'currency' => REBILL_GATEWAY_CURRENCY,
            'gatewayId' => REBILL_GATEWAY_ID
        ]),
        (new \Rebill\SDK\Models\Price)->setAttributes([
            'amount' => 1.5,
            'type' => 'fixed',
            'description' => 'Example of Subscription',
            'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'days',
                'quantity' => 2
            ]),
            'repetitions' => 2,
            'currency' => REBILL_GATEWAY_CURRENCY,
            'gatewayId' => REBILL_GATEWAY_ID
        ]),
        (new \Rebill\SDK\Models\Price)->setAttributes([
            'amount' => 0.5,
            'type' => 'fixed',
            'description' => 'Example of Unique Payment',
            'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'days',
                'quantity' => 1
            ]),
            'repetitions' => 1,
            'currency' => REBILL_GATEWAY_CURRENCY,
            'gatewayId' => REBILL_GATEWAY_ID
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
    if(isset($checkout->invoice)) {
        echo "Payment OK:\n";
        var_dump($checkout->invoice->paidBags);
    }
    if(isset($checkout->pendingTransaction)) {
        echo "Payment Pending:\n";
        var_dump($checkout->pendingTransaction->paidBags);
    }
    if(isset($checkout->failedTransaction)) {
        echo "Payment Error:\n";
        var_dump($checkout->failedTransaction->paidBags);
    }
} else {
    echo "Payment error, see log file...";
}