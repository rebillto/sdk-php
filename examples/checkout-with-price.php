<?php

include('setting.inc.php');
define('REBILL_GATEWAY_ID', '819afcd0-9c8f-413d-bbf6-8a4f0b25c6d0');
define('REBILL_GATEWAY_CURRENCY', 'USD');

$prices = [(new \Rebill\SDK\Models\Shared\CheckoutPrice)->setAttributes([
    'id' => '7f933588-e9d2-42b7-a944-22b6c64c4a36',
    'quantity' => 1
])];

$checkout = (new \Rebill\SDK\Models\Checkout)->setAttributes([
    'prices' => $prices,
    'customer' => (new \Rebill\SDK\Models\Shared\CustomerCheckout)->setAttributes([
        'email' => 'usertest@test.com',
        'firstName' => 'Test',
        'lastName' => 'Name',
        'phone' => [
            'countryCode' => '-',
            'areaCode' => '-',
            'phoneNumber' => '555555555',
        ],
        //'raw' => $address,
        'address'     => [
            'street' => 'St 123',
            'state' => 'Buenos Aires',
            'city'     => 'CABA',
            'country'     => 'AR',
            'zipCode'  => '1000',
        ],
        'card' => (new \Rebill\SDK\Models\Card)->setAttributes([
            'cardNumber' => '4111111111111111',
            'cardHolder' => [
                'name' => 'Test Card',
                'identification' => [
                    'type' => 'DNI',
                    'value' => ''.time()
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