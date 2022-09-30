<?php

include('setting.inc.php');

/*
Creación de un Item y un Price
Los atributos estan definidos en: 
 - https://docs.rebill.to/reference/itemcontroller_getprice
 - https://docs.rebill.to/reference/itemcontroller_createitem
 - https://docs.rebill.to/reference/itemcontroller_createpriceforanitem
*/

define('REBILL_GATEWAY_ID', 'b7249fce-727b-4516-8258-36046c4c5716');
define('REBILL_GATEWAY_CURRENCY', 'USD');

/*
Ejemplo de un Price con suscripción recurrente bi-mensual
*/
$result = (new \Rebill\SDK\Models\Item)->setAttributes([
    'name' => 'Fixed Item',
    'description' => 'Example of Fixed Item',
    'prices' => [
        (new \Rebill\SDK\Models\Price)->setAttributes([
            'amount' => "2.5",
            'type' => 'fixed',
            'description' => 'Example of Price Fixed',
            'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'months',
                'quantity' => 2
            ]),
            'repetitions' => null, // Infinite repetition
            'currency' => REBILL_GATEWAY_CURRENCY,
            'gatewayId' => REBILL_GATEWAY_ID
        ])
    ]
])->create();
var_dump($result->toArray());

/*
Ejemplo de un Price con suscripción recurrente bi-mensual
*/
$result = (new \Rebill\SDK\Models\Item)->setAttributes([
    'name' => 'Tiered Item',
    'description' => 'Example of tiered Item',
    'metadata' => [
        'external_reference' => 'dummy value',
        'other_meta' => 'other dummy value'
    ],
    'prices' => [
        (new \Rebill\SDK\Models\Price)->setAttributes([
            'type' => 'tiered',
            'description' => 'Example of Price tiered',
            'tiers' => [
                (new \Rebill\SDK\Models\Shared\Tiers)->setAttributes([
                    'amount' => "1.5",
                    'upTo' => 5,
                    'id' => '1231232311'
                ]),
                (new \Rebill\SDK\Models\Shared\Tiers)->setAttributes([
                    'amount' => "1",
                    'upTo' => 10,
                    'id' => '1231232322'
                ]),
            ],
            'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'months',
                'quantity' => 2
            ]),
            'freeTrial' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'months',
                'quantity' => 1
            ]),
            'repetitions' => 2,
            'currency' => REBILL_GATEWAY_CURRENCY,
            'gatewayId' => REBILL_GATEWAY_ID
        ])
    ]
])->create();
var_dump($result->toArray());

/*
Agregar un price a un Item ya existente
*/
$result = (new \Rebill\SDK\Models\Item)->setAttributes([
    'name' => 'Empty Item',
    'description' => 'Example of Empty Item',
    'prices' => [ ]
])->create();
if ($result && !empty($result->item->id) && count($result->prices) == 0) {
    $price = ( new \Rebill\SDK\Models\Price() )->setAttributes([
            'amount' => "2.5",
            'type' => 'fixed',
            'description' => 'Example of Price Fixed',
            'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'months',
                'quantity' => 2
            ]),
            'freeTrial' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'months',
                'quantity' => 1
            ]),
            'repetitions' => null, // Infinite repetition
            'currency' => self::$currency['dlocal'],
            'gatewayId' => self::$gateway['dlocal']
    ])->add($result->item->id);
    var_dump($price->toArray());
}

