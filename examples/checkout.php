<?php

include('setting.inc.php');

/*
Checkout:

NOTA IMPORTANTE: Solo disponible para clientes con certificado PCI Compliance

El resultado de un checkout sera del tipo: \Rebill\SDK\Models\Response\CheckoutResponse

Los atributos requeridos por \Rebill\SDK\Models\Checkout están definidos en https://docs.rebill.to/reference/checkoutcontroller_docheckout

Para crear un checkout se debe realizar el siguiente objeto:
*/
define('REBILL_GATEWAY_ID', 'b7249fce-727b-4516-8258-36046c4c5716');
define('REBILL_GATEWAY_CURRENCY', 'USD');

// Creamos un Item con un Price (Si aun no existe)
$result = (new \Rebill\SDK\Models\Item)->setAttributes([
    'name' => 'Testing checkout',
    'description' => 'Test of Checkout',
    'metadata' => [
        'key_of_meta1' => 'example meta 1',
        'key_of_meta2' => 'example meta 2',
    ],
    'prices' => [
        (new \Rebill\SDK\Models\Price)->setAttributes([
            'amount' => '2.5',
            'type' => 'fixed',
            'description' => 'Example of Subscription with infinite repetitions',
            'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'months',
                'quantity' => 3
            ]),
            'repetitions' => null,
            'currency' => REBILL_GATEWAY_CURRENCY,
            'gatewayId' => REBILL_GATEWAY_ID
        ]),
        (new \Rebill\SDK\Models\Price)->setAttributes([
            'amount' => 1.5,
            'type' => 'fixed',
            'description' => 'Example of Subscription with only two payment',
            'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'months',
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
                'type' => 'months',
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

/*
    O se puede realizar esto si ya existia un Price
    $prices = [
        (new \Rebill\SDK\Models\Shared\CheckoutPrice)->setAttributes([
            'id' => 'xxxxxxxx-xxxxxxxxx-xxxxxxxxx-xxxxxxx',
            'quantity' => 1
        ]),
        (new \Rebill\SDK\Models\Shared\CheckoutPrice)->setAttributes([
            'id' => 'yyyyyyyy-xxxxxxxxx-xxxxxxxxx-yyyyyyy',
            'quantity' => 1
        ])
    ];
*/


$checkout = (new \Rebill\SDK\Models\Checkout)->setAttributes([
    'prices' => $prices,
    'customer' => (new \Rebill\SDK\Models\Shared\CheckoutCustomer)->setAttributes([
        'email' => 'testuser@clientdomain.com',
        'firstName' => 'APRO Test',
        'lastName' => 'Name',
        'phone' => [
            "countryCode" => "52", // Optional with this value: "-"
            "areaCode" => "1", // Optional with this value: "-"
            "phoneNumber" => "302390203929039"
        ],
        'address' => [
            "street" => "San Jose",
            "number" => "1120", // Optional with this value: "0"
            "state" => "Buenos Aires",
            "city" => "San Isidro",
            "country" => "AR",
            "zipCode" => "2000"
        ],
        'taxId' => [
            'type' => 'CUIT', // See Rebill\SDK\Models\GatewayIdentificationTypes::get
            'value' => '222999333'
        ],
        'personalId' => [
            'type' => 'DNI', // See Rebill\SDK\Models\GatewayIdentificationTypes::get
            'value' => '111222333'
        ],
        'card' => (new \Rebill\SDK\Models\Card)->setAttributes([
            'cardNumber' => '4509953566233704',
            'cardHolder' => [
                'name' => 'APRO Test Name',
                'identification' => [
                    'type' => 'DNI', // See Rebill\SDK\Models\GatewayIdentificationTypes::get
                    'value' => '111222333'
                ]
            ],
            'securityCode' => '123',
            'expiration' => [
                'month' => 11,
                'year' => 2025
            ],
        ])
    ])
])->create();
if ($checkout) {
    /*
        Dependiendo del tipo de respuesta se conocera el status del pago.
        Cada pago generado se retornara en un arreglo de paidBags definidos
        en el objeto Rebill\SDK\Models\Shared\PaidBag
    */
    if (isset($checkout->invoice) && !empty($checkout->invoice->id)) {
        echo "Payment OK:\n";
        var_dump($checkout->invoice->paidBags);
    }
    if (isset($checkout->pendingTransaction) && !empty($checkout->pendingTransaction->id)) {
        echo "Payment Pending:\n";
        var_dump($checkout->pendingTransaction->paidBags);
    }
    if (isset($checkout->failedTransaction) && !empty($checkout->failedTransaction->id)) {
        echo "Payment Error:\n";
        foreach($checkout->failedTransaction->paidBags as $paid) {
            echo $paid->payment->status.': '.$paid->payment->errorMessage."\n";
        }
    }
} else {
    echo "Payment error, see log file...";
}